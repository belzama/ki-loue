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
            'prenom' => 'Système',
            'email' => 'admin@rentalpark.com',
            'password' => Hash::make('admin123'), // mot de passe sécurisé
            'role' => 'Admin',
            'telephone' => '91520434',
            'whatsapp' => '91520434',
            'pays_id' => $defaultPays->id ?? null,
        ]);

/*        // ➤ Générer 10 utilisateurs aléatoires (facultatif)
        \App\Models\User::factory(10)->create([
            'pays_id' => $defaultPays->id ?? null,
        ]);*/
    }
}
