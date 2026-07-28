<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pays;
use App\Mail\AccountCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('pays')->get()
        ]);
    }

    public function create()
    {
        $pays = Pays::all();
        $users = User::all();
        return view('admin.users.create', compact('pays', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'user_id' => 'nullable|exists:users,id',

            'code' => 'required|string|max:50|unique:users,code',
            'nom' => 'required|string|max:150',
            'prenom' => 'required|string|max:150',
            'raison_sociale' => 'nullable|string|max:150',

            'email' => 'required|email|unique:users,email',

            'telephone' => 'required|string|max:30',
            'whatsapp' => 'required|string|max:30',

            'type' => 'required|in:Société,Particulier',
            'role' => 'required|in:Admin,User',

            'taux_tarif_abonnement' => 'required|numeric|min:0',
            'taux_commission' => 'required|numeric|min:0',
            'taux_commission_sponsor' => 'required|numeric|min:0',
        ]);

        // 🔐 Génération automatique du mot de passe
        $generatedPassword = Str::password(10); // ex: "Xk8#mQ2!tZ"
        $data['password'] = Hash::make($generatedPassword);

        $user = User::create($data);

        // 📧 Envoi du mot de passe par email
        Mail::to($user->email)->send(new AccountCreatedMail($user, $generatedPassword));

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès. Le mot de passe a été envoyé par email.');
    }

    public function edit(User $user)
    {
        $pays = Pays::all();
        $users = User::where('id', '!=', $user->id)->get();
        return view('admin.users.edit', compact('user', 'pays', 'users'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'user_id' => 'nullable|exists:users,id',

            'code' => 'required|string|max:50|unique:users,code,' . $user->id,
            'nom' => 'required|string|max:150',
            'prenom' => 'required|string|max:150',
            'raison_sociale' => 'nullable|string|max:150',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'telephone' => 'required|string|max:30',
            'whatsapp' => 'required|string|max:30',

            'type' => 'required|in:Société,Particulier',
            'role' => 'required|in:Admin,User',

            'taux_tarif_abonnement' => 'required|numeric|min:0',
            'taux_commission' => 'required|numeric|min:0',
            'taux_commission_sponsor' => 'required|numeric|min:0',
        ]);

        $emailChanged = $data['email'] !== $user->email;
        $whatsappChanged = $data['whatsapp'] !== $user->whatsapp;

        if ($emailChanged) {
            $data['email_verified_at'] = null;
        }

        if ($whatsappChanged) {
            $data['whatsapp_verified_at'] = null;
        }

        $user->update($data);

        // 🔔 Message flash adapté selon ce qui a changé
        $warnings = [];
        if ($emailChanged) {
            $warnings[] = "l'adresse email";
        }
        if ($whatsappChanged) {
            $warnings[] = "le numéro WhatsApp";
        }

        $message = 'Utilisateur mis à jour avec succès.';
        if (!empty($warnings)) {
            $message .= ' L\'utilisateur devra reconfirmer ' . implode(' et ', $warnings) . ' à sa prochaine connexion.';
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}