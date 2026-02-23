<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesDispositifSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DB::table('categories')->pluck('id', 'nom');

        $types = [

            // ======================
            // ÉQUIPEMENTS DE BTP
            // ======================
            'Équipements de BTP' => [
                ['nom' => 'Bulldozer', 'min' => 150000, 'max' => 400000],
                ['nom' => 'Pelle hydraulique', 'min' => 120000, 'max' => 350000],
                ['nom' => 'Tractopelle', 'min' => 100000, 'max' => 250000],
                ['nom' => 'Niveleuse', 'min' => 180000, 'max' => 450000],
                ['nom' => 'Grue mobile', 'min' => 200000, 'max' => 600000],
                ['nom' => 'Camion benne', 'min' => 80000, 'max' => 200000],
                ['nom' => 'Compacteur', 'min' => 70000, 'max' => 180000],
                ['nom' => 'Chargeuse', 'min' => 120000, 'max' => 300000],
            ],

            // ======================
            // ÉQUIPEMENTS INFORMATIQUES
            // ======================
            'Équipements informatiques' => [
                ['nom' => 'Ordinateur portable', 'min' => 5000, 'max' => 25000],
                ['nom' => 'Ordinateur de bureau', 'min' => 7000, 'max' => 30000],
                ['nom' => 'Serveur', 'min' => 30000, 'max' => 150000],
                ['nom' => 'Imprimante', 'min' => 3000, 'max' => 20000],
                ['nom' => 'Vidéoprojecteur', 'min' => 10000, 'max' => 60000],
                ['nom' => 'Onduleur (UPS)', 'min' => 4000, 'max' => 25000],
            ],
        ];

        foreach ($types as $categorieNom => $listeTypes) {

            if (!isset($categories[$categorieNom])) {
                continue;
            }

            foreach ($listeTypes as $type) {
                DB::table('types_dispositifs')->insert([
                    'categorie_id' => $categories[$categorieNom],
                    'nom' => $type['nom'],
                    'tarif_min' => $type['min'],
                    'tarif_max' => $type['max'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
