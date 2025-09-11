<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Utilisateur;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Utilisateur::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'jean.dupont@example.com',
            'password' => bcrypt('password'),
            'telephone' => '0600000000',
            'adresse' => '1 rue de Paris',
            'date_naissance' => '1990-01-01',
            'pseudo' => 'jeandupont',
        ]);
    }
}
