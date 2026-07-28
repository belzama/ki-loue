<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Pays;

class ProfileController extends Controller
{
    // ProfileController.php
    public function show()
    {
        $pays = Pays::all();
        return view('user.profile.show', compact('pays'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom'           => 'required|string|max:255',
            'prenom'        => 'required|string|max:255',
            'raison_sociale'=> 'nullable|string|max:255',
            'telephone'     => 'required|string|max:20',
            'whatsapp'      => 'nullable|string|max:20',
            'whatsapp_notifications_opt_in' => 'nullable|boolean',
        ]);

        auth()->user()->update($request->only('nom', 'prenom', 'raison_sociale', 'telephone', 'whatsapp'));

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }
}
