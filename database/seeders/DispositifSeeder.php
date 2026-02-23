<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispositif;
use App\Models\User;
use App\Models\TypesDispositif;

class DispositifSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer un utilisateur ayant le rôle User
        $user = User::where('role', 'User')->first();

        if (!$user) {
            $this->command->warn('Aucun utilisateur avec le rôle User trouvé.');
            return;
        }

        $types = TypesDispositif::all();

        if ($types->isEmpty()) {
            $this->command->warn('Aucun type de dispositif trouvé.');
            return;
        }

        $dispositifs = [
            [
                'type' => 'Bulldozer',
                'numero_immatriculation' => 'TG-4655-BR',
                'designation' => 'Catapillar ZS',
                'description' => 'Bulldozer Caterpillar D6 en excellent état',
            ],
            [
                'type' => 'Pelle hydraulique',
                'numero_immatriculation' => 'TG-4658-BR',
                'designation' => 'Catapillar HR42',
                'description' => 'Pelle hydraulique Komatsu PC200',
            ],
            [
                'type' => 'Grue mobile',
                'numero_immatriculation' => 'TG-4555-BR',
                'designation' => 'Catapillar Mini',
                'description' => 'Grue mobile',
            ],
            [
                'type' => 'Serveur',
                'numero_immatriculation' => '5468879',
                'designation' => 'HP PROLIANT ML 30 GEN',
                'description' => 'Serveur HP Proliant Gen 30 16 Go RAM et 2 To DD',
            ],
        ];

        foreach ($dispositifs as $item) {
            $type = $types->firstWhere('nom', $item['type']);

            if (!$type) {
                $this->command->warn("Type introuvable : {$item['type']}");
                continue;
            }

            Dispositif::create([
                'user_id' => $user->id,
                'types_dispositif_id' => $type->id,
                'numero_immatriculation' => $item['numero_immatriculation'],
                'designation' => $item['designation'],
                'description' => $item['description'],
                'statut' => 'actif',
            ]);
        }
    }
}
