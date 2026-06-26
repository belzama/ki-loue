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
            ['nom' => 'Bulldozer', 'image_link' => 'categories/bulldozer.png'],
            ['nom' => 'Niveleuse', 'image_link' => 'categories/niveleuse.png'],
            ['nom' => 'Pelle mécanique', 'image_link' => 'categories/pelle.png'],
            ['nom' => 'Tractopelle', 'image_link' => 'categories/tractopelle.png'],
            ['nom' => 'Chargeuse', 'image_link' => 'categories/chargeuse.png'],
            ['nom' => 'Compacteur', 'image_link' => 'categories/compacteur.png'],
            ['nom' => 'Recycleuse/Raboteuse', 'image_link' => 'categories/raboteuse.png'],
            ['nom' => 'Finisseur', 'image_link' => 'categories/finisseur.png'],
            ['nom' => 'Centrale à béton/enrobé', 'image_link' => 'categories/centrale-beton.png'],
            ['nom' => 'Camion citerne', 'image_link' => 'categories/camion-citerne.png'],
            ['nom' => 'Camion benne', 'image_link' => 'categories/camion-benne.png'],
            ['nom' => 'Tombereau', 'image_link' => 'categories/tombereau.png'],
            ['nom' => 'Véhicule articulé', 'image_link' => 'categories/vehicule-articule.png'],
            ['nom' => 'Camion plateau', 'image_link' => 'categories/camion-plateau.png'],
            ['nom' => 'Camion grue', 'image_link' => 'categories/camion-grue.png'],
            ['nom' => 'Véhicule utilitaire', 'image_link' => 'categories/vehicule-utilitaire.png'],
            ['nom' => 'Coffrage/Échafaudage', 'image_link' => 'categories/echafaudage.png'],
            ['nom' => 'Auxiliaires de démolition', 'image_link' => 'categories/demolition.png'],
            ['nom' => 'Bétonnière', 'image_link' => 'categories/betonniere.png'],
            ['nom' => 'Auto-bétonnière', 'image_link' => 'categories/auto-betonniere.png'],
            ['nom' => 'Camion toupie', 'image_link' => 'categories/camion-toupie.png'],
            ['nom' => 'Pompe à béton', 'image_link' => 'categories/pompe-beton.png'],
            ['nom' => 'Élévateur/Télescopique', 'image_link' => 'categories/elevateur.png'],
            ['nom' => 'Grue', 'image_link' => 'categories/grue.png'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}
