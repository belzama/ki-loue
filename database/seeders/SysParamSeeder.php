<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SysParam;

class SysParamSeeder extends Seeder
{
    public function run(): void
    {
        $params = [
            [
                'code'  => 'APP_NAME',
                'value' => 'Ki-Loue',
                'desc'  => 'Nom de l’application',
            ],
            [
                'code'  => 'APP_VERSION',
                'value' => '1.0.0',
                'desc'  => 'Version de l’application',
            ],
            [
                'code'  => 'DEFAULT_LANG',
                'value' => 'fr',
                'desc'  => 'Langue par défaut',
            ],
            [
                'code'  => 'LOCAL_CURRENCY_CODE',
                'value' => 'XOF',
                'desc'  => 'Devise local',
            ],
            [
                'code'  => 'SMS_PROVIDER',
                'value' => 'INFOBIP',
                'desc'  => 'Fournisseur SMS',
            ],
            [
                'code'  => 'SUPPORT_PHONE',
                'value' => '+22890000000',
                'desc'  => 'Numéro du support client',
            ],
            [
                'code'  => 'COMMISSION_RATE',
                'value' => '0.10',
                'desc'  => 'Taux de commission par publication',
            ],
            [
                'code'  => 'SPONSOR_BONUS',
                'value' => '10000',
                'desc'  => 'Montant bonus parrainage',
            ],
            [
                'code'  => 'SPONSOR_RATE_NEW',
                'value' => '0.25',
                'desc'  => 'Taux du bonus reversé au filleul',
            ],
        ];

        foreach ($params as $param) {
            SysParam::updateOrCreate(
                ['code' => $param['code']],
                [
                    'value' => $param['value'],
                    'desc'  => $param['desc'],
                ]
            );
        }
    }
}
