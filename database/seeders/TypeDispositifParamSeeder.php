<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeDispositifParamSeeder extends Seeder
{
    public function run(): void
    {
        $params = [
            // Terrassement & Extraction (Aucune astérisque sur la liste fournie)
            ['type' => 'Bulldozer', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Niveleuse (Grader)', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Pelle mécanique sur chenilles', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Pelle mécanique sur pneus', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Tractopelle chargeuse', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Tractopelle pelleteuse', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Chargeuse sur pneus', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Chargeuse sur chenilles', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],

            // Compactage & Route
            ['type' => 'Compacteur vivant monocylindre', 'name' => 'poids_utile', 'label' => 'Poids Utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Compacteur vivant monocylindre ( y/c pied de mouton)', 'name' => 'poids_utile', 'label' => 'Poids Utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Recycleuse de chaussée', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'CV', 'type_v' => 'int', 'req' => false],
            ['type' => 'Chariot élevateur', 'name' => 'charge_levage', 'label' => 'Charge de levage', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Compacteur à pneux', 'name' => 'poids_utile', 'label' => 'Poids Utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Compacteur cylindre tandem', 'name' => 'poids_utile', 'label' => 'Poids Utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Plaque vibrante', 'name' => 'poids', 'label' => 'Poids', 'unit' => 'Kg', 'type_v' => 'decimal', 'req' => false],
            ['type' => 'Pilonneuse / Dame sauteuse', 'name' => 'poids', 'label' => 'Poids', 'unit' => 'Kg', 'type_v' => 'decimal', 'req' => false],
            ['type' => 'Finisseur', 'name' => 'largeur_pose', 'label' => 'Largeur de pose', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Raboteuse routière', 'name' => 'largeur_fraisage', 'label' => 'Largeur de fraisage', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Centrale d’enrobé', 'name' => 'capacite', 'label' => 'Capacité', 'unit' => 'Tonne/heure', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Epandeur de bitume', 'name' => 'capacite_cuve', 'label' => 'Capacité cuve', 'unit' => 'Litres', 'type_v' => 'int', 'req' => true],
            ['type' => 'Balayeuse voirie', 'name' => 'largeur_balayage', 'label' => 'Largeur de balayage', 'unit' => 'Mètre', 'type_v' => 'decimal', 'req' => false],

            // Transport & Levage
            ['type' => 'Camion benne', 'name' => 'volume_benne', 'label' => 'Volume benne', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Tombereau rigide', 'name' => 'volume_benne', 'label' => 'Volume benne', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Tombereau articulé', 'name' => 'volume_benne', 'label' => 'Volume benne', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Mini-tombereau', 'name' => 'volume_benne', 'label' => 'Volume benne', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Semi-remorque', 'name' => 'volume_benne', 'label' => 'Volume benne', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Chariot télescopique (Manitou)', 'name' => 'charge_levage', 'label' => 'Charge de levage', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Porte-engin ( Porte Char )', 'name' => 'charge_utile', 'label' => 'Charge utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Camion plateau', 'name' => 'charge_utile', 'label' => 'Charge utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Camion plateau grue', 'name' => 'charge_levage', 'label' => 'Charge de levage', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Mini camion de liaison', 'name' => 'charge_utile', 'label' => 'Charge utile', 'unit' => 'Tonne', 'type_v' => 'decimal', 'req' => false],
            ['type' => 'Pickup', 'name' => 'nb_places', 'label' => 'Nombre de places', 'unit' => 'Places', 'type_v' => 'int', 'req' => false],

            // Matériel de Chantier
            ['type' => 'Groupe électrogène', 'name' => 'puissance', 'label' => 'Puissance', 'unit' => 'KVA', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Compresseur d’air', 'name' => 'pression', 'label' => 'Pression', 'unit' => 'Bars', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Compacteur rouleau manuel', 'name' => 'charge_utile', 'label' => 'Charge utile', 'unit' => 'Kg', 'type_v' => 'decimal', 'req' => false],
            ['type' => 'Compacteur mini-rouleau tandem', 'name' => 'charge_utile', 'label' => 'Charge utile', 'unit' => 'Kg', 'type_v' => 'decimal', 'req' => false],
            ['type' => 'Vibreur béton', 'name' => 'rendement', 'label' => 'Rendement', 'unit' => 'm3/h', 'type_v' => 'decimal', 'req' => false],
            ['type' => 'Cuve carburant mobile', 'name' => 'capacite_cuve', 'label' => 'Capacité cuve', 'unit' => 'Litres', 'type_v' => 'int', 'req' => true],
            ['type' => 'Tour d’éclairage chantier', 'name' => 'puissance_projecteurs', 'label' => 'Puissance projecteurs', 'unit' => 'Watt', 'type_v' => 'int', 'req' => true],
            ['type' => 'Panneaux de Coffrage métallique', 'name' => 'surface', 'label' => 'Surface', 'unit' => 'm2', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Panneaux de Coffrage en bois', 'name' => 'surface', 'label' => 'Surface', 'unit' => 'm2', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Etais métalliques', 'name' => 'hauteur_utile', 'label' => 'Hauteur utile', 'unit' => 'Mètre', 'type_v' => 'decimal', 'req' => true],

            // Démolition & Découpe
            ['type' => 'Brise-roche hydraulique (BRH)', 'name' => 'poids_pression', 'label' => 'Poids/Pression', 'unit' => 'Kg/bars', 'type_v' => 'string', 'req' => true],
            ['type' => 'Pince de démolition', 'name' => 'pression', 'label' => 'Pression', 'unit' => 'bars', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Cisaille hydraulique', 'name' => 'longueur_coupe', 'label' => 'Longueur de coupe', 'unit' => 'Mètre', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Marteau piqueur', 'name' => 'puissance_frappe', 'label' => 'Puissance de frappe', 'unit' => 'Joule', 'type_v' => 'int', 'req' => false],
            ['type' => 'Scie à sol', 'name' => 'profondeur_coupe', 'label' => 'Profondeur de coupe', 'unit' => 'mm', 'type_v' => 'int', 'req' => true],
            ['type' => 'Scie à béton', 'name' => 'profondeur_coupe', 'label' => 'Profondeur de coupe', 'unit' => 'mm', 'type_v' => 'int', 'req' => true],

            // Béton
            ['type' => 'Centrale à béton', 'name' => 'capacite', 'label' => 'Capacité', 'unit' => 'Tonne/heure', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Centrale pour sol stabilisé', 'name' => 'capacite', 'label' => 'Capacité', 'unit' => 'Tonne/heure', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Centrale à béton bitumineux', 'name' => 'capacite', 'label' => 'Capacité', 'unit' => 'Tonne/heure', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Bétonnière chantier', 'name' => 'capacite_malaxage', 'label' => 'Capacité malaxage', 'unit' => 'Litres', 'type_v' => 'int', 'req' => true],
            ['type' => 'Bétonnière auto-chargeuse', 'name' => 'capacite_malaxage', 'label' => 'Capacité malaxage', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Camion toupie', 'name' => 'capacite_cuve', 'label' => 'Capacité cuve', 'unit' => 'm3', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Pompe à béton mobile', 'name' => 'debit', 'label' => 'Débit', 'unit' => 'm3/h', 'type_v' => 'decimal', 'req' => true],
            ['type' => 'Pompe à béton sur camion', 'name' => 'portee_debit', 'label' => 'Portée flèche/débit', 'unit' => 'Mètre/ m3/h', 'type_v' => 'string', 'req' => true],
        ];

        foreach ($params as $param) {
            $typeDispositif = DB::table('types_dispositifs')->where('nom', $param['type'])->first();

            if ($typeDispositif) {
                DB::table('type_dispositif_params')->updateOrInsert(
                    [
                        'types_dispositif_id' => $typeDispositif->id,
                        'name' => $param['name']
                    ],
                    [
                        'label' => $param['label'],
                        'value_type' => $param['type_v'],
                        'numeric_value_unit' => $param['unit'],
                        'required' => $param['req'],
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }
}