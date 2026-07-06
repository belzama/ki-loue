<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarif;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarifs = [
            [
                'pays_id' => 1,
                'designation' => 'Tranche 1',
                'tranche_debut' => 1,
                'tranche_fin' => 3,
                'tranche_valeur' => 0.02105,
            ],
            [
                'pays_id' => 1,
                'designation' => 'Tranche 2',
                'tranche_debut' => 4,
                'tranche_fin' => 7,
                'tranche_valeur' => 0.01055,
            ],
            [
                'pays_id' => 1,
                'designation' => 'Tranche 3',
                'tranche_debut' => 8,
                'tranche_fin' => 15,
                'tranche_valeur' => 0.00530,
            ],
            [
                'pays_id' => 1,
                'designation' => 'Tranche 4',
                'tranche_debut' => 16,
                'tranche_fin' => 31,
                'tranche_valeur' => 0.00210,
            ],
        ];

        foreach ($tarifs as $tarif) {
            Tarif::create($tarif);
        }
    }
}