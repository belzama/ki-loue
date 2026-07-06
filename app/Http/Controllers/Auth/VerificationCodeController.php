<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\VerificationCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationCodeController extends Controller
{
    public function showForm()
    {
        if (!session('verification_user_id')) {
            return redirect()->route('register')
                ->with('error', 'Session expirée. Veuillez vous réinscrire.');
        }

        return view('auth.verify-code');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId = session('verification_user_id');

        if (!$userId) {
            return response()->json(['message' => 'Session expirée.'], 422);
        }

        $user = User::findOrFail($userId);

        if (!VerificationCodeService::verify($user, $request->code)) {
            return response()->json(['message' => 'Code invalide ou expiré.'], 422);
        }

        session()->forget('verification_user_id');
        Auth::login($user);

        return response()->json([
            'message'  => 'Email vérifié !',
            'redirect' => route('user.dashboard'),
        ]);
    }

    public function resend(Request $request)
    {
        $userId = session('verification_user_id');

        if (!$userId) {
            return response()->json(['message' => 'Session expirée.'], 422);
        }

        $user = User::findOrFail($userId);

        if (
            $user->email_verification_code_expires_at &&
            now()->diffInSeconds($user->email_verification_code_expires_at) > 540
        ) {
            return response()->json(['message' => 'Patientez avant de renvoyer un code.'], 429);
        }

        VerificationCodeService::send($user);

        return response()->json(['message' => 'Nouveau code envoyé à ' . $user->email]);
    }
}