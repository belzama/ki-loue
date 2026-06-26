<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periodicite;

class PeriodiciteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodicites = [
            [
                'libelle' => 'Mensuelle',
                'couleur' => 'primary',
                'nb_jour' => 30,
                'qte' => 1,
                'taux_remise' => 0,
            ],
            [
                'libelle' => 'Trimestrielle',
                'couleur' => 'success',
                'nb_jour' => 90,
                'qte' => 3,
                'taux_remise' => 5,
            ],
            [
                'libelle' => 'Semestrielle',
                'couleur' => 'warning',
                'nb_jour' => 180,
                'qte' => 60,
                'taux_remise' => 10,
            ],
            [
                'libelle' => 'Annuelle',
                'couleur' => 'secondary',
                'nb_jour' => 365,
                'qte' => 12,
                'taux_remise' => 15,
            ],
        ];

        foreach ($periodicites as $periodicite) {
            Periodicite::create($periodicite);
        }
    }
}