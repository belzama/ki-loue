<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pays;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer un pays par défaut
        $defaultPays = Pays::first();

        // ➤ Administrateur
        User::create([
            'code' => 'ADMIN',
            'nom' => 'Administrateur',
            'email' => 'admin@Ki-loue.com',
            'password' => Hash::make('admin123'), // mot de passe sécurisé
            'role' => 'Admin',
            'pays_id' => $defaultPays->id ?? null,
        ]);

/*        // ➤ Générer 10 utilisateurs aléatoires (facultatif)
        \App\Models\User::factory(10)->create([
            'pays_id' => $defaultPays->id ?? null,
        ]);*/
    }
}
