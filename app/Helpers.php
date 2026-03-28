<?php

use Illuminate\Support\Facades\Auth;
use App\Models\SysParam;
use App\Models\Pays;

if (!function_exists('sys_param')) {
    function sys_param(string $code, $default = null)
    {
        return SysParam::where('code', $code)->value('value') ?? $default;
    }
}

if (!function_exists('getUserCountry')) {
    /**
     * Récupère le pays de l'utilisateur ou un pays par défaut
     */
    function getUserCountry()
    {
        $user = Auth::user();
        
        // On utilise le helper request() au lieu de la variable $request
        $userIp = request()->ip();
        
        // Gestion du localhost pour le test (si IP est ::1, on simule une IP réelle)
        $ip = ($userIp == '::1' || $userIp == '127.0.0.1') ? '8.8.8.8' : $userIp;
        
        // On récupère la position via le package Location
        $position = \Stevebauman\Location\Facades\Location::get($ip);
        
        // Code pays par défaut (Togo)
        $defaultCode = 'TG';
        $countryCode = $position?->countryCode ?? $defaultCode;

        // Priorité 1 : Pays lié au profil de l'utilisateur (relation Eloquent)
        if ($user && $user->pays) {
            return $user->pays;
        }

        // Priorité 2 : Pays basé sur la position (IP/Geo) ou fallback sur TG
        return Pays::where('code', $countryCode)->first() 
            ?? Pays::where('code', $defaultCode)->first();
    }
}
