<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traitement de la connexion
     */
    public function login(Request $request)
    {
        // ✅ Validation : email OU pseudo
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        // 🔀 Détection automatique email ou code
        $credentials = [
            filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'code' => $login,
            'password' => $request->password,
        ];

        // 🔐 Tentative de connexion
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // 🔀 Redirection selon rôle
            if (auth()->user()->role === 'Admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        // ❌ Échec
        return back()->withErrors([
            'login' => 'Email / code ou mot de passe incorrect.',
        ])->withInput();
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
