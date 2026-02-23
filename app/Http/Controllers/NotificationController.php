<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Affiche la liste des notifications de l'utilisateur connecté
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('user.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'message' => 'required|string',
            'email_address' => 'nullable|email',
            'whatsapp_number' => 'nullable|string',
        ]);

        Notification::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'message' => $request->message,
            'send_email' => $request->has('send_email'),
            'send_whatsapp' => $request->has('send_whatsapp'),
            'email_address' => $request->email_address,
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        return redirect()->route('user.notifications.index')
            ->with('status', 'Notification créée avec succès.');
    }


    /**
     * Marquer une notification spécifique comme lue
     */
    public function markAsRead(Notification $notification)
    {
        // Sécurité : vérifier que la notification appartient bien à l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read' => true]);

        return back()->with('status', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('read', false)->update(['read' => true]);

        return back()->with('status', 'Toutes les notifications ont été lues.');
    }

    /**
     * Supprimer une notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('status', 'Notification supprimée.');
    }
}
