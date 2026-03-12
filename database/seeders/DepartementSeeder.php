<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération des pays par nom
        $regions = DB::table('regions')->pluck('id', 'nom');

        $departements = [

            // ======================
            // TOGO - REGION MARITIME
            // ======================
            'Région maritime' => [
                ['nom' => 'Avé'],
                ['nom' => 'Golfe'],
                ['nom' => 'Lacs'],
                ['nom' => 'Vo'],
                ['nom' => 'Yoto'],
                ['nom' => 'Zio'],
                ['nom' => 'Agoè-Nyivé'],
                ['nom' => 'Bas-Mono'],
            ],

            // ======================
            // TOGO - REGION DES PLATEAUX
            // ======================
            'Région des plateaux' => [
                ['nom' => 'Agou'],
                ['nom' => 'Amou'],
                ['nom' => 'Danyi'],
                ['nom' => 'Est-Mono'],
                ['nom' => 'Haho'],
                ['nom' => 'Kloto'],
                ['nom' => 'Moyen-Mono'],
                ['nom' => 'Ogou'],
                ['nom' => 'Wawa'],
                ['nom' => 'Akébou'],
                ['nom' => 'Anié'],
                ['nom' => 'Kpélé'],
            ],

            // ======================
            // TOGO - REGION CENTRALE
            // ======================
            'Région centrale' => [
                ['nom' => 'Blitta'],
                ['nom' => 'Sotouboua'],
                ['nom' => 'Tchamba'],
                ['nom' => 'Tchaoudjo'],
                ['nom' => 'Mô'],
            ],

            // ======================
            // TOGO - REGION DE LA KARA
            // ======================
            'Région de la kara' => [
                ['nom' => 'Assoli'],
                ['nom' => 'Bassar'],
                ['nom' => 'Binah'],
                ['nom' => 'Dankpen'],
                ['nom' => 'Doufelgou'],
                ['nom' => 'Kéran'],
                ['nom' => 'Kozah'],
            ],

            // ======================
            // TOGO - REGION DES SAVANES
            // ======================
            'Région des savanes' => [
                ['nom' => 'Kpendjal'],
                ['nom' => 'Oti'],
                ['nom' => 'Tandjouaré'],
                ['nom' => 'Tône'],
                ['nom' => 'Cinkassé'],
                ['nom' => 'Oti-Sud'],
                ['nom' => 'Kpendjal-Ouest'],
            ],
        ];

        foreach ($departements as $nomRegion => $listeDepartements) {

            if (!isset($regions[$nomRegion])) {
                continue;
            }

            foreach ($listeDepartements as $departement) {
                DB::table('departements')->insert([
                    'region_id' => $regions[$nomRegion],
                    'nom' => $departement['nom'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
