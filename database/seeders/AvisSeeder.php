<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Avis;
use App\Models\Utilisateur;
use App\Models\Covoiturage;

class AvisSeeder extends Seeder
{
    public function run(): void
    {

        $utilisateur = Utilisateur::first();
        $covoiturage = Covoiturage::first();

        if (!$utilisateur || !$covoiturage) {
            return;
        }
        
        Avis::create([
            'utilisateur_id' => 1,
            'covoiturage_id' => 1,
            'note' => 5,
            'commentaire' => 'Très bon trajet !',
        ]);
    }
}