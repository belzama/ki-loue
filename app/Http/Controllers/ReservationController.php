<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Notification;

class ReservationController extends Controller
{
    /**
     * Liste des réservations
     */
    public function index()
    {
        $reservations = Reservation::with('publication')
            ->latest()
            ->paginate(10);

        return view('user.reservations.index', compact('reservations'));
    }

    /**
     * Formulaire d'approbation
     */
    public function approveForm($id)
    {
        $reservation = Reservation::findOrFail($id);

        return view('user.reservations.approve', compact('reservation'));
    }

    /**
     * Validation (accordée)
     */
    public function approve(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'date_accordee' => 'required|date',
            'duree_accordee' => 'required|integer|min:1',
        ]);

        $reservation->update([
            'date_accordee' => $request->date_accordee,
            'duree_accordee' => $request->duree_accordee,
            'motif_apporbation' => $request->motif_apporbation,
            'statut' => 'Accordée'
        ]);

        //changer le statut de la publication
        $reservation->publication->update([
            'active' => 0
        ]);

        //Création de la notification
        // 1. Préparer le message proprement
        $dispositif = $reservation->publication->dispositif;

        $message = "Votre réservation pour le dispositif " . $dispositif->designation . 
                " immatriculé " . $dispositif->numero_immatriculation . 
                " pour " . $request->duree_demandee . 
                " jour(s) à partir du " . $request->date_demandee .
                " a été acceptée";

        // 2. Créer la notification
        Notification::create([
            'user_id'       => auth()->id(),
            'type'          => 'Réservation',
            'message'       => $message,
            // On envoie l'email SEULEMENT SI l'utilisateur a une adresse email
            'send_email'    => !empty($reservation->email), 
            'send_email_address'    => $reservation->email, 
            // On envoie WhatsApp SEULEMENT SI l'utilisateur a un contact
            'send_whatsapp' => !empty($reservation->telephone),
            'send_whatsapp_contact' => $reservation->telephone,
        ]);

        return redirect()->route('user.reservations.index')
            ->with('success', 'Réservation accordée.');
    }

    /**
     * Formulaire de rejet
     */
    public function rejectForm($id)
    {
        $reservation = Reservation::findOrFail($id);

        return view('user.reservations.reject', compact('reservation'));
    }

    /**
     * Refus
     */
    public function reject(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->update([
            'motif_apporbation' => $request->motif_apporbation,
            'statut' => 'Rejetée'
        ]);
        //Création de la notification
        // 1. Préparer le message proprement
        $dispositif = $reservation->publication->dispositif;

        $message = "Votre réservation pour le dispositif " . $dispositif->designation . 
                " immatriculé " . $dispositif->numero_immatriculation . 
                " pour " . $reservation->duree_demandee . 
                " jour(s) à partir du " . $reservation->date_demandee .
                " a été rejetée pour cause de : " . $request->motif_apporbation . ".";

        // 2. Créer la notification
        Notification::create([
            'user_id'       => auth()->id(),
            'type'          => 'Réservation',
            'message'       => $message,
            // On envoie l'email SEULEMENT SI l'utilisateur a une adresse email
            'send_email'    => !empty($reservation->email), 
            'send_email_address'    => $reservation->email, 
            // On envoie WhatsApp SEULEMENT SI l'utilisateur a un contact
            'send_whatsapp' => !empty($reservation->telephone),
            'send_whatsapp_contact' => $reservation->telephone,
        ]);

        return redirect()->route('user.reservations.index')
            ->with('success', 'Réservation rejetée.');
    }

    public function show(Reservation $reservation)
    {
        return view('user.reservations.show', compact('reservation'));
    }
}
