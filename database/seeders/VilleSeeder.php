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
            'Togo' => [
                'Lomé',
                'Tsévié',
                'Aného',
                'Atakpamé',
                'Notsè',
                'Kpalimé',
                'Badou',
                'Sokodé',
                'Blitta',
                'Kara',
                'Bafilo',
                'Dapaong',
                'Mango',
                'Anié',
                'Cinkassé',
                'Tabligbo',
                'Tchamba',
                'Elavanyo',
                'Tohoun',
                'Djarkpanga',
                'Amlamé',
                'Kougnohou',
                'Mandouri',
                'Pagouda',
                'Danyi Apéyémé',
                'Naki-Centre',
                'Kévé',
                'Agou Gadzépé',
                'Tandjoaré',
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
