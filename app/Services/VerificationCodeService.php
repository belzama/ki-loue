<?php
namespace App\Services;
use App\Models\User;
use App\Mail\EmailVerificationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class VerificationCodeService
{
    public static function send(User $user): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'email_verification_code'            => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new EmailVerificationCode($code));
    }

    public static function verify(User $user, string $code): bool
    {
        if (!$user->email_verification_code_expires_at ||
            now()->isAfter($user->email_verification_code_expires_at)) {
            return false;
        }

        if (!Hash::check($code, $user->email_verification_code)) {
            return false;
        }

        $user->update([
            'email_verified_at'                  => now(),
            'email_verification_code'            => null,
            'email_verification_code_expires_at' => null,
        ]);

        return true;
    }
}