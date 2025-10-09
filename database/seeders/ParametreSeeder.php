<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parametre;
use App\Models\Configuration;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        $config = Configuration::first();

        Parametre::create([
            'propriete' => 'Langue',
            'valeur' => 'fr',
            'configuration_id' => $config->id,
        ]);

        Parametre::create([
            'propriete' => 'DevMode',
            'valeur' => 'true',
            'configuration_id' => $config->id,
        ]);
    }
}
