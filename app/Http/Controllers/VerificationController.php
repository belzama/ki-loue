<?php

namespace App\Http\Controllers;

use App\Models\VerificationCode;
use App\Mail\VerificationCodeMail;
use App\Support\VerificationConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['type' => 'required|in:email,telephone,whatsapp']);

        $user = Auth::user();
        $type = $request->type;
        $contact = $type === 'email' ? $user->email : $user->{$type};

        if (empty($contact)) {
            return response()->json(['message' => 'Aucune information à vérifier pour ce canal.'], 422);
        }

        $code = (string) random_int(100000, 999999);

        VerificationCode::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type],
            ['code' => $code, 'expires_at' => now()->addMinutes(10)]
        );

        $sent = match ($type) {
            'email' => (bool) Mail::to($user->email)->send(new VerificationCodeMail($code)) || true,
            'telephone' => $this->infobip->sendSms($contact, "Votre code de vérification RentalPark : {$code}"),
            'whatsapp' => $this->infobip->sendWhatsappTemplate($contact, 'verification_code', [$code]),
        };

        if (!$sent) {
            return response()->json(['message' => 'Échec de l\'envoi du code. Réessayez.'], 500);
        }

        return response()->json([
            'message' => 'Code envoyé.',
            'contact' => $this->maskContact($contact, $type),
        ]);
    }

    private function maskContact(string $contact, string $type): string
    {
        if ($type === 'email') {
            [$name, $domain] = explode('@', $contact);
            $visible = substr($name, 0, 2);
            return $visible . str_repeat('*', max(strlen($name) - 2, 1)) . '@' . $domain;
        }

        // téléphone / whatsapp : garde les 4 derniers chiffres visibles
        $visiblePart = substr($contact, -4);
        $hiddenLength = max(strlen($contact) - 4, 0);
        return str_repeat('*', $hiddenLength) . $visiblePart;
    }

    public function verify(Request $request)
    {
        $request->validate([
            'type' => 'required|in:email,telephone,whatsapp',
            'code' => 'required|string',
        ]);

        $user = Auth::user();

        $verification = VerificationCode::where('user_id', $user->id)
            ->where('type', $request->type)
            ->latest()
            ->first();

        if (!$verification || $verification->code !== $request->code || $verification->isExpired()) {
            return response()->json(['message' => 'Code invalide ou expiré.'], 422);
        }

        $user->update([$request->type . '_verified_at' => now()]);
        $verification->delete();

        return response()->json([
            'success' => true,
            'remaining' => VerificationConfig::pendingTypes($user->fresh()),
        ]);
    }
}