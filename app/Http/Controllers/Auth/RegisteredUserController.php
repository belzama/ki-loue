<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pays;
use App\Services\VerificationCodeService;
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
    public function store(Request $request): RedirectResponse|\Illuminate\Http\JsonResponse
    {   
        $request->validate([
            'nom'            => ['required', 'string', 'max:150'],
            'prenom'         => ['required', 'string', 'max:150'],
            'type'           => 'required|in:Société,Particulier',
            'raison_sociale' => ['required_if:type,Société', 'nullable', 'string', 'max:150'],
            'code'           => ['nullable', 'string', 'max:50'],
            'email'          => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
            'pays_id'        => ['required', 'exists:pays,id'],
            'telephone'      => 'required|string|max:30',
            'whatsapp'       => 'required|string|max:30',
            'ref_code'       => ['nullable', 'exists:users,code'],
        ]);

        $sponsor = $request->filled('ref_code')
            ? User::where('code', $request->ref_code)->first()
            : null;

        $user = User::create([
            'pays_id'        => $request->pays_id,
            'user_id'        => $sponsor?->id,
            'code'           => $request->code,
            'nom'            => $request->nom,
            'prenom'         => $request->prenom,
            'type'           => $request->type,
            'raison_sociale' => $request->raison_sociale,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'telephone'      => $request->telephone,
            'whatsapp'       => $request->whatsapp,
            'role'           => 'User',
        ]);

        $user->appliquerBonusParrainage();

        event(new Registered($user));

        VerificationCodeService::send($user);
        session(['verification_user_id' => $user->id]);

        // ✅ Réponse JSON pour AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Compte créé ! Un code a été envoyé à ' . $user->email,
            ]);
        }

        return redirect()->route('register')->with('show_verify_modal', true);
    }
}
