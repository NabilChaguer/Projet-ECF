<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;

class MarqueSeeder extends Seeder
{
    public function run(): void
    {
        $marque = ['Peugeot', 'Renault', 'Citroën', 'Toyota', 'Ford'];

        foreach ($marque as $marque) {
            Marque::create([
                'libelle' => $marque,
            ]);
        }
    }
}