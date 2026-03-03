<?php

// database/seeders/PaysSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pays;
use App\Models\Continent;
use App\Models\Devise;

class PaysSeeder extends Seeder
{
    public function run(): void
    {
        $afrique = Continent::where('nom', 'Afrique de l\'ouest')->first();
        $xof = Devise::where('code', 'XOF')->first();
        $ghs = Devise::where('code', 'GHS')->first();
        $ngn = Devise::where('code', 'NGN')->first();

        $pays = [
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'TG',
                'indicatif' => '+228',
                'nom' => 'Togo',
                'nationalite' => 'Togolaise',
                'langue_officielle' => 'Français',
                'taux_commission' => 2.5,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
        ];

        foreach ($pays as $p) {
            Pays::create($p);
        }
    }
}
