<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pays;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Afficher le formulaire d'inscription
     */
    public function create(): View
    {
        $pays = Pays::orderBy('nom')->get();

        return view('auth.register', compact('pays'));
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom'      => ['required', 'string', 'max:255'],
            'code'      => ['nullable', 'string', 'max:50'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'pays_id'  => ['required', 'exists:pays,id'],
            'contact'  => ['nullable', 'string', 'max:50'],
            'ref_code' => ['nullable', 'exists:users,code'],
        ]);

        // Recherche du sponsor
        $sponsor = null;

        if ($request->filled('ref_code')) {
            $sponsor = User::where('code', $request->ref_code)->first();
        }

        $user = User::create([
            'pays_id'   => $request->pays_id,
            'user_id'   => $sponsor, // sponsor si nécessaire
            'code'      => $request->code,
            'nom'       => $request->nom,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'contact'   => $request->contact,
            'role'      => 'User',
        ]);
        
        // 🔥 Appliquer bonus de parrainage
        $user->appliquerBonusParrainage();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}
