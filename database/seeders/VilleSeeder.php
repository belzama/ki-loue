<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VilleSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération des pays par nom
        $pays = DB::table('pays')->pluck('id', 'nom');

        $villes = [

            // ======================
            // TOGO
            // ======================
            'Togo - Région maritime' => [
                'Lomé',
                'Tsévié',
                'Aného',
            ],
            'Togo - Région des plateaux' => [
                'Atakpamé',
                'Notsè',
                'Kpalimé',
                'Badou',
            ],
            'Togo - Région centrale' => [
                'Sokodé',
                'Blitta',
            ],
            'Togo - Région de la kara' => [
                'Kara',
                'Bafilo',
            ],
            'Togo - Région des savanes' => [
                'Dapaong',
                'Mango',
            ],
        ];

        foreach ($villes as $nomPays => $listeVilles) {

            if (!isset($pays[$nomPays])) {
                continue;
            }

            foreach ($listeVilles as $ville) {
                DB::table('villes')->insert([
                    'pays_id' => $pays[$nomPays],
                    'nom' => $ville,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
