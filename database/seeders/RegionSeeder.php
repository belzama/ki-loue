<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pays;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pays_list = DB::table('pays')->pluck('id', 'nom');

        $regions = [
            'Bénin' => [
                ['nom' => 'Alibori'],
                ['nom' => 'Atacora'],
                ['nom' => 'Atlantique'],
                ['nom' => 'Borgou'],
                ['nom' => 'Collines'],
                ['nom' => 'Couffo'],
                ['nom' => 'Donga'],
                ['nom' => 'Littoral'],
                ['nom' => 'Mono'],
                ['nom' => 'Ouémé'],
                ['nom' => 'Plateau'],
                ['nom' => 'Zou']
            ],
            'Burkina Faso' => [
                ['nom' => 'Bankui'],
                ['nom' => 'Sourou'],
                ['nom' => 'Kadiogo'],
                ['nom' => 'Nakambé'],
                ['nom' => 'Moogo'],
                ['nom' => 'Bulkiemdé'],
                ['nom' => 'Zoundwéogo'],
                ['nom' => 'Comoé'],
                ['nom' => 'Guiriko'],
                ['nom' => 'Gourma'],
                ['nom' => 'Sirba'],
                ['nom' => 'Tapoa'],
                ['nom' => 'Yatenga'],
                ['nom' => 'Oubritenga'],
                ['nom' => 'Liptako'],
                ['nom' => 'Sum'],
                ['nom' => 'Djôrô']
            ],
            'Côte d\'ivoire' => [
                ['nom' => 'Abidjan'],
                ['nom' => 'Yamoussoukro'],
                ['nom' => 'Bas-Sassandra'],
                ['nom' => 'Comoé'],
                ['nom' => 'Denguélé'],
                ['nom' => 'Gôh-Djiboua'],
                ['nom' => 'Lacs'],
                ['nom' => 'Lagunes'],
                ['nom' => 'Montagnes'],
                ['nom' => 'Sassandra-Marahoué'],
                ['nom' => 'Savanes'],
                ['nom' => 'Vallée du Bandama'],
                ['nom' => 'Woroba'],
                ['nom' => 'Zanzan']
            ],
            'Guinée Bissau' => [
                ['nom' => 'Bissau'],
                ['nom' => 'Biombo'],
                ['nom' => 'Cacheu'],
                ['nom' => 'Oio'],
                ['nom' => 'Bafatá'],
                ['nom' => 'Gabú'],
                ['nom' => 'Bolama-Bijagós'],
                ['nom' => 'Quínara'],
                ['nom' => 'Tombali']
            ],
            'Mali' => [
                ['nom' => 'Kayes'],
                ['nom' => 'Koulikoro'],
                ['nom' => 'Sikasso'],
                ['nom' => 'Ségou'],
                ['nom' => 'Mopti'],
                ['nom' => 'Tombouctou'],
                ['nom' => 'Gao'],
                ['nom' => 'Kidal'],
                ['nom' => 'Taoudénit'],
                ['nom' => 'Ménaka'],
                ['nom' => 'Nioro'],
                ['nom' => 'Kita'],
                ['nom' => 'Dioïla'],
                ['nom' => 'Nara'],
                ['nom' => 'Bougouni'],
                ['nom' => 'Koutiala'],
                ['nom' => 'San'],
                ['nom' => 'Douentza'],
                ['nom' => 'Bandiagara'],
                ['nom' => 'Bamako']
            ],
            'Niger' => [
                ['nom' => 'Agadez'],
                ['nom' => 'Diffa'],
                ['nom' => 'Dosso'],
                ['nom' => 'Maradi'],
                ['nom' => 'Tahoua'],
                ['nom' => 'Tillabéri'],
                ['nom' => 'Zinder'],
                ['nom' => 'Niamey']
            ],
            'Sénégal' => [
                ['nom' => 'Dakar'],
                ['nom' => 'Diourbel'],
                ['nom' => 'Fatick'],
                ['nom' => 'Kaffrine'],
                ['nom' => 'Kaolack'],
                ['nom' => 'Kédougou'],
                ['nom' => 'Kolda'],
                ['nom' => 'Louga'],
                ['nom' => 'Matam'],
                ['nom' => 'Saint-Louis'],
                ['nom' => 'Sédhiou'],
                ['nom' => 'Tambacounda'],
                ['nom' => 'Thiès'],
                ['nom' => 'Ziguinchor']
            ],
            'Togo' => [
                ['nom' => 'Maritime'],
                ['nom' => 'Plateaux'],
                ['nom' => 'Centrale'],
                ['nom' => 'Kara'],
                ['nom' => 'Savanes']
            ],
        ];

        foreach ($regions as $paysNom => $listeRegions) {

            if (!isset($pays_list[$paysNom])) {
                continue;
            }

            foreach ($listeRegions as $region) {
                DB::table('regions')->insert([
                    'pays_id' => $pays_list[$paysNom],
                    'nom' => $region['nom'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
