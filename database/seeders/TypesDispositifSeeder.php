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
            // TERRASSEMENT
            // ======================
            'Matériels de terrassement' => [
                ['nom' => 'Bulldozer', 'min' => 250000, 'max' => 800000],
                ['nom' => 'Niveleuse (Grader)', 'min' => 200000, 'max' => 700000],
                ['nom' => 'Pelle mécanique sur chenilles', 'min' => 250000, 'max' => 800000],
                ['nom' => 'Pelle mécanique sur pneus', 'min' => 200000, 'max' => 700000],
                ['nom' => 'Tractopelle chargeuse', 'min' => 150000, 'max' => 350000],
                ['nom' => 'Tractopelle pelleteuse', 'min' => 150000, 'max' => 350000],
                ['nom' => 'Chargeuse sur pneus', 'min' => 120000, 'max' => 500000],
                ['nom' => 'Chargeuse sur chenilles', 'min' => 150000, 'max' => 600000],
                ['nom' => 'Compacteur vibrant monocylindre', 'min' => 100000, 'max' => 350000],
                ['nom' => 'Compacteur vibrant monocylindre ( y/c pied de mouton)', 'min' => 150000, 'max' => 400000],
                ['nom' => 'Recycleuse de chaussée', 'min' => 600000, 'max' => 1200000],
                ['nom' => 'Chariot élevateur', 'min' => 80000, 'max' => 350000],
            ],

            // ======================
            // BITUMAGE
            // ======================
            'Matériels de bitumage' => [
                ['nom' => 'Compacteur à pneux', 'min' => 100000, 'max' => 400000],
                ['nom' => 'Compacteur cylindre tandem', 'min' => 100000, 'max' => 350000],
                ['nom' => 'Plaque vibrante', 'min' => 10000, 'max' => 100000],
                ['nom' => 'Pilonneuse / Dameuse', 'min' => 10000, 'max' => 100000],
                ['nom' => 'Finisseur', 'min' => 400000, 'max' => 1500000],
                ['nom' => 'Raboteuse routière', 'min' => 500000, 'max' => 1800000],
                ['nom' => 'Centrale d’enrobé', 'min' => 600000, 'max' => 2500000],
                ['nom' => 'Epandeur de bitume', 'min' => 100000, 'max' => 400000],
                ['nom' => 'Balayeuse voirie', 'min' => 80000, 'max' => 300000],
            ],

            // ======================
            // TRANSPORT
            // ======================
            'Matériels de transport' => [
                ['nom' => 'Camion benne', 'min' => 70000, 'max' => 300000],
                ['nom' => 'Tombereau rigide', 'min' => 150000, 'max' => 800000],
                ['nom' => 'Tombereau articulé', 'min' => 150000, 'max' => 1000000],
                ['nom' => 'Mini-tombereau', 'min' => 40000, 'max' => 200000],
                ['nom' => 'Semi-remorque', 'min' => 100000, 'max' => 300000],
                ['nom' => 'Chariot télescopique (Manitou)', 'min' => 70000, 'max' => 250000],
                ['nom' => 'Porte-engin (Porte Char)', 'min' => 200000, 'max' => 1000000],
                ['nom' => 'Camion plateau', 'min' => 70000, 'max' => 200000],
                ['nom' => 'Camion plateau grue', 'min' => 80000, 'max' => 300000],
                ['nom' => 'Mini camion de liaison', 'min' => 60000, 'max' => 150000],
                ['nom' => 'Pickup', 'min' => 150000, 'max' => 150000],
            ],

            // ======================
            // AUXILIAIRES DE CHANTIER
            // ======================
            'Matériels auxiliaires de chantier' => [
                ['nom' => 'Groupe électrogène', 'min' => 5000, 'max' => 100000],
                ['nom' => 'Compresseur d’air', 'min' => 15000, 'max' => 200000],
                ['nom' => 'Compacteur rouleau manuel ', 'min' => 10000, 'max' => 100000],
                ['nom' => 'Compacteur mini-rouleau tandem', 'min' => 25000, 'max' => 150000],
                ['nom' => 'Vibreur béton', 'min' => 5000, 'max' => 25000],
                ['nom' => 'Cuve carburant mobile', 'min' => 5000, 'max' => 50000],
                ['nom' => 'Tour d’éclairage chantier', 'min' => 10000, 'max' => 100000],
                ['nom' => 'Panneaux de Coffrage métallique', 'min' => 500, 'max' => 7500],
                ['nom' => 'Panneaux de Coffrage en bois', 'min' => 500, 'max' => 5000],
                ['nom' => 'Etais métalliques', 'min' => 50, 'max' => 150],
            ],

            // ======================
            // DEMOLITION
            // ======================
            'Matériels de démolition' => [
                ['nom' => 'Brise-roche hydraulique (BRH)', 'min' => 100000, 'max' => 750000],
                ['nom' => 'Pince de démolition', 'min' => 35000, 'max' => 300000],
                ['nom' => 'Cisaille hydraulique', 'min' => 25000, 'max' => 250000],
                ['nom' => 'Marteau piqueur', 'min' => 5000, 'max' => 100000],
                ['nom' => 'Scie à sol', 'min' => 5000, 'max' => 125000],
                ['nom' => 'Scie à béton', 'min' => 5000, 'max' => 100000],
            ],

            // ======================
            // PRODUCTION DE BETON
            // ======================
            'Matériels de production du béton' => [
                ['nom' => 'Centrale à béton', 'min' => 100000, 'max' => 1200000],
                ['nom' => 'Centrale pour sol stabilisé', 'min' => 100000, 'max' => 1000000],
                ['nom' => 'Centrale à béton bitumineux', 'min' => 150000, 'max' => 1500000],
                ['nom' => 'Bétonnière chantier', 'min' => 15000, 'max' => 200000],
                ['nom' => 'Bétonnière auto-chargeuse', 'min' => 25000, 'max' => 250000],
                ['nom' => 'Camion toupie', 'min' => 70000, 'max' => 200000],
                ['nom' => 'Pompe à béton mobile', 'min' => 100000, 'max' => 500000],
                ['nom' => 'Pompe à béton sur camion', 'min' => 300000, 'max' => 1500000],
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
