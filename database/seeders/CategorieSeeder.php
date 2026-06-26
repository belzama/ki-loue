<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nom' => 'Bulldozer', 'image_link' => 'categories/bulldozer.jpg'],
            ['nom' => 'Niveleuse', 'image_link' => 'categories/niveleuse.jpg'],
            ['nom' => 'Pelle mécanique', 'image_link' => 'categories/pelle.jpg'],
            ['nom' => 'Tractopelle', 'image_link' => 'categories/tractopelle.jpg'],
            ['nom' => 'Chargeuse', 'image_link' => 'categories/chargeuse.jpg'],
            ['nom' => 'Compacteur', 'image_link' => 'categories/compacteur.jpg'],
            ['nom' => 'Recycleuse/Raboteuse', 'image_link' => 'categories/raboteuse.jpg'],
            ['nom' => 'Finisseur', 'image_link' => 'categories/finisseur.jpg'],
            ['nom' => 'Centrale à béton/enrobé', 'image_link' => 'categories/centrale-beton.jpg'],
            ['nom' => 'Camion citerne', 'image_link' => 'categories/camion-citerne.jpg'],
            ['nom' => 'Camion benne', 'image_link' => 'categories/camion-benne.jpg'],
            ['nom' => 'Tombereau', 'image_link' => 'categories/tombereau.jpg'],
            ['nom' => 'Véhicule articulé', 'image_link' => 'categories/vehicule-articule.jpg'],
            ['nom' => 'Camion plateau', 'image_link' => 'categories/camion-plateau.jpg'],
            ['nom' => 'Camion grue', 'image_link' => 'categories/camion-grue.jpg'],
            ['nom' => 'Véhicule utilitaire', 'image_link' => 'categories/vehicule-utilitaire.jpg'],
            ['nom' => 'Coffrage/Échafaudage', 'image_link' => 'categories/echafaudage.jpg'],
            ['nom' => 'Auxiliaires de démolition', 'image_link' => 'categories/demolition.jpg'],
            ['nom' => 'Bétonnière', 'image_link' => 'categories/betonniere.jpg'],
            ['nom' => 'Auto-bétonnière', 'image_link' => 'categories/auto-betonniere.jpg'],
            ['nom' => 'Camion toupie', 'image_link' => 'categories/camion-toupie.jpg'],
            ['nom' => 'Pompe à béton', 'image_link' => 'categories/pompe-beton.jpg'],
            ['nom' => 'Élévateur/Télescopique', 'image_link' => 'categories/elevateur.jpg'],
            ['nom' => 'Grue', 'image_link' => 'categories/grue.jpg'],
        ];

        foreach ($categories as $nom) {
            Categorie::create(['nom' => $nom]);
        }
    }
}
