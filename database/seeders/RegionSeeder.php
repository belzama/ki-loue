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
            'Togo' => [
                ['nom' => 'Région maritime'],
                ['nom' => 'Région des plateaux'],
                ['nom' => 'Région centrale'],
                ['nom' => 'Région de la kara'],
                ['nom' => 'Région des savanes']
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
