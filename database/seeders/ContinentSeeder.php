<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Continent;

class ContinentSeeder extends Seeder
{
    public function run(): void
    {
        $continents = [
            'Afrique de l\'ouest',
        ];

        foreach ($continents as $nom) {
            Continent::create(['nom' => $nom]);
        }
    }
}
