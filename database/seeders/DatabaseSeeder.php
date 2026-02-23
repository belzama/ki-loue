<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DeviseSeeder::class,
            ContinentSeeder::class,
            PaysSeeder::class,
            //VilleSeeder::class,
            UserSeeder::class,
            //CategorieSeeder::class,
            //TypesDispositifSeeder::class,    
            //DispositifSeeder::class,             
            //DispositifPhotoSeeder::class,          
            SysParamSeeder::class,           
        ]);
        /*
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
