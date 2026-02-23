<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispositif;
use App\Models\DispositifPhoto;

class DispositifPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $dispositifs = Dispositif::all();

        if ($dispositifs->isEmpty()) {
            $this->command->warn('Aucun dispositif trouvé.');
            return;
        }

        foreach ($dispositifs as $dispositif) {

            // Photo principale
            DispositifPhoto::create([
                'dispositif_id' => $dispositif->id,
                'path' => 'dispositifs/default_cover.jpg',
                'is_cover' => true,
            ]);

            // Photos secondaires
            for ($i = 1; $i <= 3; $i++) {
                DispositifPhoto::create([
                    'dispositif_id' => $dispositif->id,
                    'path' => "dispositifs/sample_{$i}.jpg",
                    'is_cover' => false,
                ]);
            }
        }
    }
}
