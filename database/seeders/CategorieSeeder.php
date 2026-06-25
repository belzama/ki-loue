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
            'Bulldozer',
            'Niveleuse',
            'Pelle mécanique',
            'Tractopelle',
            'Chargeuse',
            'Compacteur',
            'Recycleuse/Raboteuse',
            'Finisseur',
            'Centrale à béton/enrobé',
            'Camion citerne',
            'Camion benne',
            'Tombereau',
            'Vehicule articulé',
            'Camion plateau',
            'Camion grue',
            'Vehicule utilitaire',
            'Coffrage/Echaffaudage',
            'Auxiliaires de démolition',
            'Bétonnière',
            'Auto-bétonnière',
            'Camion toupie',
            'Pompe à béton',
            'Elevateur/Télescopique',
            'Grue',
        ];

        foreach ($categories as $nom) {
            Categorie::create(['nom' => $nom]);
        }
    }
}
