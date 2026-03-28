<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Stevebauman\Location\Facades\Location;

use App\Models\Pays;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Categorie;
use App\Models\TypesDispositif;
use App\Models\Publication;
use App\Models\Reservation;
use App\Models\Notification;

class HomeController extends Controller
{
    /**
     * Page d'accueil avec les publications actives
     */
    public function index(Request $request)
    {
        // On cherche le pays, mais on prévoit un fallback (ex: 'FR' par défaut)
        $country = getUserCountry();

        // Requête de base avec eager loading
        $query = Publication::with([
            'dispositif.photos',
            'dispositif.type_dispositif.categorie',
            'departement.region.pays',
            'devise'
        ])
        ->where('active', 1)
        ->where('date_debut', '<=', now())
        ->where('date_fin', '>=', now());

        /*
        |----------------------------------------------------------------------
        | FILTRES
        |----------------------------------------------------------------------
        */

        // Filtre par Continent via Departement → Région → Pays → Continent
        /*if ($request->filled('continent_id')) {
            $query->whereHas('departement.region.pays', function($q) use ($request){
                $q->whereHas('continent', function($q2) use ($request){
                    $q2->where('id', $request->continent_id);
                });
            });
        } */       

        // Filtre par Pays via Departement → Région → Pays
        if ($request->filled('pays_id')) {
            $query->whereHas('departement.region.pays', function($q) use ($request){
                $q->where('id', $request->pays_id);
            });
        }

        // Filtre par Région via Departement → Région
        if ($request->filled('region_id')) {
            $query->whereHas('departement.region', function($q) use ($request){
                $q->where('id', $request->region_id);
            });
        }

        // Filtre par Departement
        if ($request->filled('departement_id')) {
            $query->where('departement_id', $request->departement_id);
        }

        // Filtre par Categorie via TypeDispositif → Dispositif
        if ($request->filled('categorie_id')) {
            $query->whereHas('dispositif.type_dispositif.categorie', function ($q) use ($request) {
                $q->where('id', $request->categorie_id);
            });
        }

        // Filtre par Type de dispositif via Dispositif
        if ($request->filled('types_dispositif_id')) {
            $query->whereHas('dispositif', function ($q) use ($request) {
                $q->where('types_dispositif_id', $request->types_dispositif_id);
            });
        }

        // Filtre par Tarif minimum
        if ($request->filled('tarif_min')) {
            $query->where('tarif_location', '>=', $request->tarif_min);
        }

        // Filtre par Tarif maximum
        if ($request->filled('tarif_max')) {
            $query->where('tarif_location', '<=', $request->tarif_max);
        }

        /*
        |----------------------------------------------------------------------
        | PAGINATION
        |----------------------------------------------------------------------
        */

        $publications = $query
            ->latest()
            ->paginate(12)
            ->withQueryString(); // garde les filtres dans l'URL

        /*
        |----------------------------------------------------------------------
        | DONNÉES POUR LES FILTRES
        |----------------------------------------------------------------------
        */

        $categories = Categorie::orderBy('nom')->get();
        $typesDispositifs = TypesDispositif::orderBy('nom')->get();
        $pays             = Pays::orderBy('nom')->get();
        $regions             = Region::orderBy('nom')->get();
        $departements             = Departement::orderBy('nom')->get();

        return view('welcome', compact(
            'country',
            'publications',
            'categories',
            'typesDispositifs',
            'pays',
            'regions',
            'departements'
        ));
    }

    /**
     * Afficher une publication spécifique
     */
    public function show(Publication $publication)
    {
        $publication->load([
            'dispositif.photos',
            'dispositif.type_dispositif',
            'departement.region.pays',
            'devise'
        ]);

        return view('publications.show', compact('publication'));
    }

    /**
     * Formulaire de réservation pour une publication
     * accessible sans authentification
     */
    public function createReservation(Publication $publication)
    {
        return view('reservations.create', compact('publication'));
    }

    /**
     * Enregistrer une réservation
     */
    public function storeReservation(Request $request, Publication $publication)
    {
        $data = $request->validate([
            'nom_prenom'       => 'nullable|string|max:255',
            'email'            => 'nullable|email|max:255',
            'telephone'        => 'nullable|string|max:50',
            'date_demandee'    => 'nullable|date',
            'duree_demandee'   => 'nullable|integer',
            'message'          => 'nullable|string',
        ]);

        // Création de la réservation
        $reservation = Reservation::create([
            'publication_id'   => $publication->id,
            'user_id'          => auth()->id(), // null si non connecté
            'date_reservation' => now()->toDateString(),
            'date_demandee'    => $data['date_demandee'] ?? null,
            'duree_demandee'   => $data['duree_demandee'] ?? 1,
            'nom_prenom'       => $data['nom_prenom'] ?? null,
            'email'            => $data['email'] ?? null,
            'message'          => $data['message'] ?? null,
            'telephone'        => $data['telephone'] ?? null,
            'statut'           => 'Demandée',
        ]);

        try {

            DB::beginTransaction();

            // =============================
            // Création réservation
            // =============================
            $reservation = Reservation::create([
                'publication_id'   => $publication->id,
                'user_id'          => auth()->id(),
                'date_reservation' => now()->toDateString(),
                'date_demandee'    => $data['date_demandee'] ?? null,
                'duree_demandee'   => $data['duree_demandee'] ?? 1,
                'nom_prenom'       => $data['nom_prenom'] ?? null,
                'email'            => $data['email'] ?? null,
                'telephone'        => $data['telephone'] ?? null,
                'message'          => $data['message'] ?? null,
                'statut'           => 'Demandée',
            ]);

            // =============================
            // Notification
            // =============================
            $dispositif = $publication->dispositif;
            $owner = $dispositif->user;

            $notificationMessage = "Demande de réservation du dispositif "
                . $dispositif->designation
                . " immatriculé "
                . $dispositif->numero_immatriculation;

            Notification::create([
                'user_id'              => $owner->id,
                'type'                 => 'Réservation',
                'message'              => $notificationMessage,
                'send_email'           => !empty($owner->email),
                'send_email_address'   => $owner->email,
                'send_whatsapp'        => !empty($owner->whatsapp),
                'send_whatsapp_number' => $owner->whatsapp,
            ]);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Une erreur est survenue.'
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue.');
        }

        // =============================
        // Construction message WhatsApp
        // =============================
        $lienReservation = route('user.reservations.show', $reservation->id);

        $message  = "Bonjour,\n\n";
        $message .= "Je souhaite réserver votre matériel *"
            . $publication->dispositif->designation . "*\n\n";

        if (!empty($data['message'])) {
            $message .= "Message : " . $data['message'] . "\n\n";
        }

        $message .= "Voir la réservation :\n" . $lienReservation;

        $messageEncoded = urlencode($message);

        // =============================
        // Retour Ajax
        // =============================
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reservation_id' => $reservation->id,
                'owner' => [
                    'telephone' => $owner->telephone,
                    'whatsapp' => $owner->whatsapp,
                    'email'   => $owner->email,
                ],
                'message' => $messageEncoded
            ]);
        }

        // =============================
        // Retour normal
        // =============================
        return back()->with('success', 'Votre demande de réservation a été envoyée !');
    }
}

