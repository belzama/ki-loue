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
                ['nom' => 'Bulldozer', 'min' => 250000, 'max' => 800000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Niveleuse', 'min' => 200000, 'max' => 700000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Pelle mécanique sur chenilles', 'min' => 250000, 'max' => 800000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Pelle mécanique sur pneus', 'min' => 200000, 'max' => 700000], 'nom_dispositif_fields' => 'marque,modele',
                ['nom' => 'Tractopelle chargeuse', 'min' => 150000, 'max' => 350000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Tractopelle pelleteuse', 'min' => 150000, 'max' => 350000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Chargeuse sur pneus', 'min' => 120000, 'max' => 500000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Chargeuse sur chenilles', 'min' => 150000, 'max' => 600000], 'nom_dispositif_fields' => 'marque,modele',
                ['nom' => 'Compacteur vibrant monocylindre', 'min' => 100000, 'max' => 350000, 'nom_dispositif_fields' => 'marque,modele,poids_utile'],
                ['nom' => 'Compacteur vibrant avec pied de mouton', 'min' => 150000, 'max' => 400000], 'nom_dispositif_fields' => 'marque,modele,poids_utile',
                ['nom' => 'Recycleuse de chaussée', 'min' => 600000, 'max' => 1200000, 'nom_dispositif_fields' => 'marque,modele'],
            ],

            // ======================
            // BITUMAGE
            // ======================
            'Matériels de bitumage' => [
                ['nom' => 'Compacteur à pneux', 'min' => 100000, 'max' => 400000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Compacteur cylindre tandem', 'min' => 100000, 'max' => 350000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Plaque vibrante', 'min' => 10000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Pilonneuse / Dameuse', 'min' => 10000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Finisseur', 'min' => 400000, 'max' => 1500000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Raboteuse routière', 'min' => 500000, 'max' => 1800000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Centrale d’enrobé', 'min' => 600000, 'max' => 2500000, 'nom_dispositif_fields' => 'marque,modele,capacite'],
                ['nom' => 'Epandeur de bitume', 'min' => 100000, 'max' => 400000, 'nom_dispositif_fields' => 'marque,modele,capacite_cuve'],
                ['nom' => 'Balayeuse voirie', 'min' => 80000, 'max' => 300000], 'nom_dispositif_fields' => 'marque,modele',
            ],

            // ======================
            // TRANSPORT
            // ======================
            'Matériels de transport' => [
                ['nom' => 'Camion benne', 'min' => 70000, 'max' => 300000, 'nom_dispositif_fields' => 'marque,modele,volume_benne'],
                ['nom' => 'Tombereau rigide', 'min' => 150000, 'max' => 800000, 'nom_dispositif_fields' => 'marque,modele,volume_benne'],
                ['nom' => 'Tombereau articulé', 'min' => 150000, 'max' => 1000000, 'nom_dispositif_fields' => 'marque,modele,volume_benne'],
                ['nom' => 'Mini-tombereau', 'min' => 40000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,volume_benne'],
                ['nom' => 'Semi-remorque', 'min' => 100000, 'max' => 300000, 'nom_dispositif_fields' => 'marque,modele,volume_benne'],
                ['nom' => 'Porte-engin (Porte Char)', 'min' => 200000, 'max' => 1000000, 'nom_dispositif_fields' => 'marque,modele,charge_utile'],
                ['nom' => 'Camion plateau', 'min' => 70000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,charge_utile'],
                ['nom' => 'Camion plateau grue', 'min' => 80000, 'max' => 300000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
                ['nom' => 'Mini camion de liaison', 'min' => 60000, 'max' => 150000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Pickup', 'min' => 150000, 'max' => 150000, 'nom_dispositif_fields' => 'marque,modele'],
            ],

            // ======================
            // AUXILIAIRES DE CHANTIER
            // ======================
            'Matériels auxiliaires de chantier' => [
                ['nom' => 'Groupe électrogène', 'min' => 5000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele,puissance'],
                ['nom' => 'Compresseur d’air', 'min' => 15000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,pression'],
                ['nom' => 'Compacteur rouleau manuel ', 'min' => 10000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Compacteur mini-rouleau tandem', 'min' => 25000, 'max' => 150000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Vibreur béton', 'min' => 5000, 'max' => 25000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Cuve carburant mobile', 'min' => 5000, 'max' => 50000, 'nom_dispositif_fields' => 'marque,modele,capacite_cuve'],
                ['nom' => 'Tour d’éclairage chantier', 'min' => 10000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele,puissance_projecteurs'],
                ['nom' => 'Panneaux de Coffrage métallique', 'min' => 500, 'max' => 7500, 'nom_dispositif_fields' => 'marque,modele,surface'],
                ['nom' => 'Panneaux de Coffrage en bois', 'min' => 500, 'max' => 5000, 'nom_dispositif_fields' => 'marque,modele,surface'],
                ['nom' => 'Etais métalliques', 'min' => 50, 'max' => 150, 'nom_dispositif_fields' => 'marque,modele,hauteur_utile'],
                ['nom' => 'Benne à béton', 'min' => 50, 'max' => 150, 'nom_dispositif_fields' => 'marque,modele,volume_benne'],
            ],

            // ======================
            // DEMOLITION
            // ======================
            'Matériels de démolition' => [
                ['nom' => 'Brise-roche hydraulique (BRH)', 'min' => 100000, 'max' => 750000, 'nom_dispositif_fields' => 'marque,modele,poids_pression'],
                ['nom' => 'Pince de démolition', 'min' => 35000, 'max' => 300000, 'nom_dispositif_fields' => 'marque,modele,pression'],
                ['nom' => 'Cisaille hydraulique', 'min' => 25000, 'max' => 250000, 'nom_dispositif_fields' => 'marque,modele,longueur_coupe'],
                ['nom' => 'Marteau piqueur', 'min' => 5000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Scie à sol', 'min' => 5000, 'max' => 125000, 'nom_dispositif_fields' => 'marque,modele'],
                ['nom' => 'Scie à béton', 'min' => 5000, 'max' => 100000, 'nom_dispositif_fields' => 'marque,modele'],
            ],

            // ======================
            // PRODUCTION DE BETON
            // ======================
            'Matériels de production du béton' => [
                ['nom' => 'Centrale à béton', 'min' => 100000, 'max' => 1200000, 'nom_dispositif_fields' => 'marque,modele,capacite'],
                ['nom' => 'Centrale pour sol stabilisé', 'min' => 100000, 'max' => 1000000, 'nom_dispositif_fields' => 'marque,modele,capacite'],
                ['nom' => 'Centrale à béton bitumineux', 'min' => 150000, 'max' => 1500000, 'nom_dispositif_fields' => 'marque,modele,capacite'],
                ['nom' => 'Bétonnière chantier', 'min' => 15000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,capacite_malaxage'],
                ['nom' => 'Bétonnière auto-chargeuse', 'min' => 25000, 'max' => 250000, 'nom_dispositif_fields' => 'marque,modele,capacite_malaxage'],
                ['nom' => 'Camion toupie', 'min' => 70000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,capacite_cuve'],
                ['nom' => 'Pompe à béton mobile', 'min' => 100000, 'max' => 500000, 'nom_dispositif_fields' => 'marque,modele,debit'],
                ['nom' => 'Pompe à béton sur camion', 'min' => 300000, 'max' => 1500000, 'nom_dispositif_fields' => 'marque,modele,portee_debit'],
            ],

            // ======================
            // LEVAGE ET MANUTENTION
            // ======================
            'Matériels de levage et de manutention' => [
                ['nom' => 'Chariot élevateur', 'min' => 80000, 'max' => 350000], 'nom_dispositif_fields' => 'marque,modele,charge_levage',
                ['nom' => 'Chariot télescopique', 'min' => 70000, 'max' => 250000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
                ['nom' => 'Nacelle sur camion', 'min' => 100000, 'max' => 1200000, 'nom_dispositif_fields' => 'marque,modele,hauteur_bras'],
                ['nom' => 'Monte-charge de chantier', 'min' => 100000, 'max' => 1000000, 'nom_dispositif_fields' => 'marque,modele,charge_utile'],
                ['nom' => 'Treuil de levage', 'min' => 150000, 'max' => 1500000, 'nom_dispositif_fields' => 'marque,modele,charge_utile'],
                ['nom' => 'Grue à tour de chantier', 'min' => 15000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,hauteur_sous_crochet'],
                ['nom' => 'Grue automotrice', 'min' => 25000, 'max' => 250000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
                ['nom' => 'Grue mobile sur camion', 'min' => 70000, 'max' => 200000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
                ['nom' => 'Grue à portique', 'min' => 100000, 'max' => 500000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
                ['nom' => 'Grue à flèche relevable', 'min' => 300000, 'max' => 1500000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
                ['nom' => 'Grue sur chenilles', 'min' => 300000, 'max' => 1500000, 'nom_dispositif_fields' => 'marque,modele,charge_levage'],
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
                    'nom_dispositif_fields' => $type['nom_dispositif_fields'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
