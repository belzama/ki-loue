<?php
// app/Services/CatalogueService.php
namespace App\Services;

use App\Models\Categorie;
use App\Models\Publication;
use App\Models\Reservation;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Exception;

class CatalogueService
{
    /*
    |--------------------------------------------------------------------------
    | PUBLICATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Requête de base : publications actives et en cours de validité.
     */
    public function baseQueryPublications(): Builder
    {
        return Publication::with([
                'dispositif.photos',
                'dispositif.type_dispositif.categorie',
                'departement.region.pays',
                'devise'
            ])
            ->where('active', 1)
            ->where('date_debut', '<=', now())
            ->where('date_fin', '>=', now());
    }

    /**
     * Applique les filtres (localisation, catégorie, type, tarif, désignation).
     *
     * @param array $filtres ex: ['pays_id' => 1, 'categorie_id' => 3, 'tarif_min' => 5000]
     */
    public function applyFiltersPublications(Builder $query, array $filtres): Builder
    {
        if (!empty($filtres['pays_id'])) {
            $query->whereHas('departement.region.pays', fn ($q) => $q->where('id', $filtres['pays_id']));
        }
        if (!empty($filtres['region_id'])) {
            $query->whereHas('departement.region', fn ($q) => $q->where('id', $filtres['region_id']));
        }
        if (!empty($filtres['departement_id'])) {
            $query->where('departement_id', $filtres['departement_id']);
        }
        if (!empty($filtres['categorie_id'])) {
            $query->whereHas('dispositif.type_dispositif.categorie', fn ($q) => $q->where('id', $filtres['categorie_id']));
        }
        if (!empty($filtres['types_dispositif_id'])) {
            $query->whereHas('dispositif', fn ($q) => $q->where('types_dispositif_id', $filtres['types_dispositif_id']));
        }
        if (!empty($filtres['tarif_min'])) {
            $query->where('tarif_location', '>=', $filtres['tarif_min']);
        }
        if (!empty($filtres['tarif_max'])) {
            $query->where('tarif_location', '<=', $filtres['tarif_max']);
        }
        if (!empty($filtres['designation'])) {
            $query->whereHas('dispositif', fn ($q) => $q->where('designation', 'like', "%{$filtres['designation']}%"));
        }

        return $query;
    }

    /**
     * Recherche complète de publications : construit + filtre + retourne.
     * Paginé si $perPage est fourni, sinon collection complète.
     */
    public function searchPublications(array $filtres = [], ?int $perPage = null)
    {
        $query = $this->applyFiltersPublications($this->baseQueryPublications(), $filtres)->latest();

        return $perPage ? $query->paginate($perPage)->withQueryString() : $query->get();
    }

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    /**
     * Liste des catégories avec le nombre de publications actives par catégorie.
     */
    public function categoriesAvecPublicationsActives()
    {
        return Categorie::addSelect([
                'publications_actives_count' => Publication::selectRaw('COUNT(*)')
                    ->whereHas('dispositif', function ($q) {
                        $q->whereHas('type_dispositif', function ($q2) {
                            $q2->whereColumn('categorie_id', 'categories.id');
                        });
                    })
                    ->where('active', true)
                    ->whereDate('date_debut', '<=', now())
                    ->whereDate('date_fin', '>=', now())
            ])
            ->orderBy('nom')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | RESERVATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Crée une réservation + notifie le propriétaire, en transaction.
     *
     * @throws Exception
     */
    public function creerReservation(Publication $publication, array $data, ?int $userId = null): Reservation
    {
        return DB::transaction(function () use ($publication, $data, $userId) {
            $reservation = Reservation::create([
                'publication_id'   => $publication->id,
                'user_id'          => $userId ?? auth()->id(),
                'date_reservation' => now()->toDateString(),
                'date_demandee'    => $data['date_demandee'] ?? null,
                'duree_demandee'   => $data['duree_demandee'] ?? 1,
                'nom_prenom'       => $data['nom_prenom'] ?? null,
                'email'            => $data['email'] ?? null,
                'telephone'        => $data['telephone'] ?? null,
                'message'          => $data['message'] ?? null,
                'statut'           => 'Demandée',
            ]);

            $this->notifierProprietaireReservation($publication, $reservation);

            return $reservation;
        });
    }

    protected function notifierProprietaireReservation(Publication $publication, Reservation $reservation): void
    {
        $dispositif = $publication->dispositif;
        $owner = $dispositif->user;

        Notification::create([
            'user_id'              => $owner->id,
            'type'                 => 'Réservation',
            'message'              => "Demande de réservation du dispositif {$dispositif->designation} immatriculé {$dispositif->numero_immatriculation}",
            'send_email'           => !empty($owner->email),
            'send_email_address'   => $owner->email,
            'send_whatsapp'        => !empty($owner->whatsapp),
            'send_whatsapp_number' => $owner->whatsapp,
        ]);
    }

    public function messageWhatsappReservation(Publication $publication, Reservation $reservation, ?string $messageUtilisateur = null): string
    {
        $message = "Bonjour,\n\nJe souhaite réserver votre matériel *{$publication->dispositif->designation}*\n\n";

        if ($messageUtilisateur) {
            $message .= "Message : {$messageUtilisateur}\n\n";
        }

        $message .= "Réservation #{$reservation->id}";

        return urlencode($message);
    }
}