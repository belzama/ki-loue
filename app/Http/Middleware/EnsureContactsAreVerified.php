<?php

namespace App\Http\Middleware;

use App\Support\VerificationConfig;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class EnsureContactsAreVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        Log::info('Middleware contacts.verified exécuté', ['user' => $user?->id, 'route' => $request->route()?->getName()]);

        if ($user) {
            $required = VerificationConfig::requiredTypes();
            $pending = VerificationConfig::pendingTypes($user);

            Log::info('Vérifications', [
                'required' => $required,
                'pending' => $pending,
                'email_verified_at' => $user->email_verified_at,
                'whatsapp_verified_at' => $user->whatsapp_verified_at,
            ]);

            if (!empty($pending)) {
                View::share('pendingVerifications', $pending);
            }
        }

        return $next($request);
    }
}