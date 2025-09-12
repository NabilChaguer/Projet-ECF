<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UtilisateurSeeder::class,
            VoitureSeeder::class,
            CovoiturageSeeder::class,
            AvisSeeder::class,
            MarqueSeeder::class,
            RoleSeeder::class,
            ConfigurationSeeder::class,
            ParametreSeeder::class,
        ]);
    }
}
