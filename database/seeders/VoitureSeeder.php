<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voiture;
use App\Models\Utilisateur;

class VoitureSeeder extends Seeder
{
    public function run(): void
    {

            $utilisateur = Utilisateur::first();


            Voiture::create([
                'modele' => 'Clio',
                'immatriculation' => 'AB-123-CD',
                'energie' => 'Hybride',
                'couleur' => 'Rouge',
                'date_premiere_immatriculation' => now()->subYears(2)->toDateString(),
                'utilisateur_id' => $utilisateur->id,
            ]);
    }
}