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
                'libelle_division' => 'Région',
                'libelle_sous_division' => 'Préfecture',
                'nationalite' => 'Togolaise',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ], 
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'BJ',
                'indicatif' => '+229',
                'nom' => 'Bénin',
                'libelle_division' => 'Département',
                'libelle_sous_division' => 'Commune',
                'nationalite' => 'Béninoise',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'BF',
                'indicatif' => '+226',
                'nom' => 'Burkina Faso',
                'libelle_division' => 'Région',
                'libelle_sous_division' => 'Province',
                'nationalite' => 'Burkinabaise',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'CI',
                'indicatif' => '+225',
                'nom' => 'Côte d\'ivoire',
                'libelle_division' => 'Distrinct',
                'libelle_sous_division' => 'Région',
                'nationalite' => 'Ivoirienne',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'SN',
                'indicatif' => '+221',
                'nom' => 'Sénégal',
                'libelle_division' => 'Région',
                'libelle_sous_division' => 'Département',
                'nationalite' => 'Sénégalaise',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'ML',
                'indicatif' => '+223',
                'nom' => 'Mali',
                'libelle_division' => 'Région',
                'libelle_sous_division' => 'Cercle',
                'nationalite' => 'Malienne',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'NE',
                'indicatif' => '+227',
                'nom' => 'Niger',
                'libelle_division' => 'Région',
                'libelle_sous_division' => 'Département',
                'nationalite' => 'Nigérienne',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
            [
                'continent_id' => $afrique->id,
                'devise_id' => $xof->id,
                'code' => 'GW',
                'indicatif' => '+245',
                'nom' => 'Guinée Bissau',
                'libelle_division' => 'Région',
                'libelle_sous_division' => 'Secteur',
                'nationalite' => 'Guinéenne',
                'langue_officielle' => 'Français',
                'nb_jour_min_pub' => 3,
                'bonus_sponsor' => 1000,
                'taux_sponsor_new' => 20
            ],
        ];

        foreach ($pays as $p) {
            Pays::create($p);
        }
    }
}
