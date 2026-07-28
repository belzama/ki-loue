<?php

namespace App\Support;

use App\Models\SysParam;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class VerificationConfig
{
    protected static array $types = ['email', 'telephone', 'whatsapp'];

    protected static array $paramKeys = [
        'email'     => 'VERIFIER_EMAIL',
        'telephone' => 'VERIFIER_TELEPHONE',
        'whatsapp'  => 'VERIFIER_WHATSAPP',
    ];

    public static function requiredTypes(): array
    {
        return Cache::remember('verification_required_types', 300, function () {
            $params = SysParam::whereIn('code', array_values(self::$paramKeys))
                ->pluck('value', 'code');

            return array_values(array_filter(self::$types, function ($type) use ($params) {
                $key = self::$paramKeys[$type];
                return strtolower(trim($params[$key] ?? 'Non')) === 'oui';
            }));
        });
    }

    public static function pendingTypes(User $user): array
    {
        return array_values(array_filter(self::requiredTypes(), function ($type) use ($user) {
            $field = $type . '_verified_at';
            $contactField = $type === 'email' ? 'email' : $type;

            if (empty($user->{$contactField})) {
                return false;
            }

            return is_null($user->{$field});
        }));
    }
}