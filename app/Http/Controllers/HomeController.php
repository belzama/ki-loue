<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Continent;
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
        // 1. On initialise la requête avec les relations nécessaires
        $query = Publication::with([
            'dispositif.photos',
            'dispositif.type_dispositif',
            'ville.pays',
            'devise'
        ])->where('active', 1)
        ->where('date_debut', '<=', now())
        ->where('date_fin', '>=', now());

        // 2. Filtre par Continent (via la relation Ville -> Pays -> Continent)
        if ($request->filled('continent_id')) {
            $query->whereHas('ville.pays', function($q) use ($request) {
                $q->where('continent_id', $request->continent_id);
            });
        }

        // 3. Filtre par Pays (via la relation Ville -> Pays)
        if ($request->filled('pays_id')) {
            $query->whereHas('ville', function($q) use ($request) {
                $q->where('pays_id', $request->pays_id);
            });
        }

        // 4. Filtre par Ville
        if ($request->filled('ville_id')) {
            $query->where('ville_id', $request->ville_id);
        }

        // 5. Filtre par Type de dispositif
        if ($request->filled('types_dispositif_id')) {
            $query->whereHas('dispositif', function($q) use ($request) {
                $q->where('types_dispositif_id', $request->types_dispositif_id);
            });
        }

        // 6. Filtres par Tarifs
        if ($request->filled('tarif_min')) {
            $query->where('tarif_location', '>=', $request->tarif_min);
        }
        if ($request->filled('tarif_max')) {
            $query->where('tarif_location', '<=', $request->tarif_max);
        }

        // 7. On exécute la pagination en gardant les filtres dans l'URL (withQueryString)
        $publications = $query->latest()->paginate(50)->withQueryString();

        $typesDispositifs = TypesDispositif::all();
        $continents = Continent::all();

        return view('welcome', compact('publications', 'typesDispositifs', 'continents'));
    }

    /**
     * Afficher une publication spécifique
     */
    public function show(Publication $publication)
    {
        $publication->load([
            'dispositif.photos',
            'dispositif.type_dispositif',
            'ville.pays',
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
            'date_demandee'    => 'required|date',
            'duree_demandee'   => 'required|integer|min:1',
            'message'          => 'nullable|string',
        ]);

        // Création de la réservation
        $reservation = Reservation::create([
            'publication_id'   => $publication->id,
            'user_id'          => auth()->id(), // null si non connecté
            'date_reservation' => now()->toDateString(),
            'date_demandee'    => $data['date_demandee'],
            'duree_demandee'   => $data['duree_demandee'],
            'nom_prenom'       => $data['nom_prenom'] ?? null,
            'email'            => $data['email'] ?? null,
            'message'          => $data['message'] ?? null,
            'telephone'        => $data['telephone'] ?? null,
            'statut'           => 'Demandée',
        ]);

        //Création de la notification
        // 1. Préparer le message proprement
        $dispositif = $publication->dispositif;

        $message = "Demande de réservation du dispositif " . $dispositif->designation . 
                " immatriculé " . $dispositif->numero_immatriculation . 
                " pour " . $data['duree_demandee'] . 
                " jour(s) à partir du " . $data['date_demandee'];

        // 2. Récupérer l'utilisateur pour simplifier la lecture
        $user = $dispositif->user;

        // 3. Créer la notification
        Notification::create([
            'user_id'       => $user->id,
            'type'          => 'Réservation',
            'message'       => $message,
            // On envoie l'email SEULEMENT SI l'utilisateur a une adresse email
            'send_email'    => !empty($user->email), 
            'send_email_address'    => $user->email, 
            // On envoie WhatsApp SEULEMENT SI l'utilisateur a un contact
            'send_whatsapp' => !empty($user->contact),
            'send_whatsapp_number' => $user->contact,
        ]);

        $action = $request->action;

        if (empty($user->contact) && empty($user->email))
        {
            return redirect()->back()->with('success', 'Votre demande de réservation a été envoyée !');
        } else
        {
            // Lien vers la réservation
            $lienReservation = route('user.reservations.show', $reservation->id);

            // Construction du message
            $message  = "Bonjour,\n\n";
            $message .= "Je souhaite réserver votre *"
                . $publication->dispositif->type_dispositif->nom . " "
                . $publication->dispositif->designation . "*\n\n";

            $message .= "*Date de début :* " . $request->date_demandee . "\n";
            $message .= "*Durée :* " . $request->duree_demandee . " jour(s)\n";

            //$message .= "*Client :* " . auth()->user()->nom . "\n";
            //$message .= "*Téléphone :* " . auth()->user()->contact . "\n\n";

            if ($request->message) {
                $message .= "Message : " . $request->message . "\n\n";
            }

            $message .= "Voir la réservation :\n" . $lienReservation;

            // Encodage obligatoire pour WhatsApp
            $message = urlencode($message);

            if ($action == 'call') {
                return redirect("tel:$user->contact");
            }

            if ($action == 'whatsapp') {
                return redirect()->route('reservations.whatsapp', [
                    'telephone' => $user->contact,// . ";97020283",
                    'message' => $message
                ]);
            }

            if ($action == 'email') {

                return redirect("mailto:".$user->email);
            }
        }
    }
}
