<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Matériels de terrassement',
            'Matériels de bitumage',
            'Matériels de transport',
            'Matériels auxiliaires de chantier',
            'Matériels de démolition',
            'Matériels de production du béton',
        ];

        foreach ($categories as $nom) {
            Categorie::create(['nom' => $nom]);
        }
    }
}
