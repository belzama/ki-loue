<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\CatalogueService;

use App\Models\Pays;
use App\Models\Region;
use App\Models\Departement;
use App\Models\TypesDispositif;
use App\Models\Publication;

class HomeController extends Controller
{
    public function __construct(protected CatalogueService $catalogue) {}

    /**
     * Page d'accueil avec les publications actives
     */
    public function index(Request $request)
    {
        $country = getUserCountry();

        $publications = $this->catalogue->searchPublications([], perPage: 12);

        $categories = $this->catalogue->categoriesAvecPublicationsActives();

        return view('welcome', compact(
            'country',
            'publications',
            'categories',
        ));
    }

    /**
     * Catalogue public avec filtres (localisation, catégorie, type, tarif)
     */
    public function showCatalogue(Request $request)
    {
        $country = getUserCountry();

        $filtres = $request->only([
            'pays_id', 'region_id', 'departement_id',
            'categorie_id', 'types_dispositif_id',
            'tarif_min', 'tarif_max',
        ]);

        $publications = $this->catalogue->searchPublications($filtres, perPage: 12);

        $categories = $this->catalogue->categoriesAvecPublicationsActives();

        $typesDispositifs = TypesDispositif::orderBy('nom')->get();
        $pays             = Pays::orderBy('nom')->get();
        $regions          = Region::orderBy('nom')->get();
        $departements     = Departement::orderBy('nom')->get();

        return view('publications.index', compact(
            'country', 'publications', 'categories',
            'typesDispositifs', 'pays', 'regions', 'departements'
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

        try {
            $reservation = $this->catalogue->creerReservation($publication, $data);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Une erreur est survenue.'
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue.');
        }

        $owner = $publication->dispositif->user;

        $lienReservation = route('user.reservations.show', $reservation->id);
        $message = $this->catalogue->messageWhatsappReservation($publication, $reservation, $data['message'] ?? null)
            . '&url=' . urlencode($lienReservation); // ajustez si le format attendu diffère

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'reservation_id' => $reservation->id,
                'owner' => [
                    'telephone' => $owner->telephone,
                    'whatsapp' => $owner->whatsapp,
                    'email'   => $owner->email,
                ],
                'message' => $message
            ]);
        }

        return back()->with('success', 'Votre demande de réservation a été envoyée !');
    }
}