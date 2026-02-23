<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Devise;

class DeviseSeeder extends Seeder
{
    public function run(): void
    {
        $devises = [
            ['code' => 'XOF', 'symbol' => 'FCFA', 'libelle' => 'Franc CFA BCEAO'],
            ['code' => 'EUR', 'symbol' => '€', 'libelle' => 'Euro'],
            ['code' => 'USD', 'symbol' => '$', 'libelle' => 'Dollar américain'],
            ['code' => 'NGN', 'symbol' => '₦', 'libelle' => 'Naira nigérian'],
            ['code' => 'GHS', 'symbol' => '₵', 'libelle' => 'Cedi ghanéen'],
        ];

        foreach ($devises as $devise) {
            Devise::create($devise);
        }
    }
}
