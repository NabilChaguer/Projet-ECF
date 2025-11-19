<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Covoiturage;
use App\Models\Voiture;
use App\Models\Utilisateur; // ✅ Import du modèle User
use Carbon\Carbon;

class CovoiturageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ On récupère ou on crée un utilisateur
        $user = Utilisateur::first() ?? Utilisateur::factory()->create();

        // ✅ On récupère ou on crée une voiture liée à cet utilisateur
        $voiture = Voiture::first() ?? Voiture::factory()->create([
            'utilisateur_id' => $user->id,
        ]);

        // ✅ Premier covoiturage
        Covoiturage::create([
            'utilisateur_id' => $user->id,
            'voiture_id'     => $voiture->id,
            'nb_place'       => 3,
            'prix_personne'  => 15.00,
            'lieu_depart'    => 'Rennes',
            'lieu_arrivee'   => 'Nantes',
            'date_depart'    => Carbon::now()->addDays(1)->toDateString(),
            'heure_depart'   => Carbon::now()->addDays(1)->format('H:i:s'),
            'date_arrivee'   => Carbon::now()->addDays(1)->toDateString(),
            'heure_arrivee'  => Carbon::now()->addDays(1)->addHours(2)->format('H:i:s'),
            'statut'         => 'ouvert',
            'ecologique'     => true,
        ]);

        // ✅ Deuxième covoiturage
        Covoiturage::create([
            'utilisateur_id' => $user->id,
            'voiture_id'     => $voiture->id,
            'nb_place'       => 2,
            'prix_personne'  => 20.00,
            'lieu_depart'    => 'Paris',
            'lieu_arrivee'   => 'Lyon',
            'date_depart'    => Carbon::now()->addDays(2)->toDateString(),
            'heure_depart'   => Carbon::now()->addDays(2)->format('H:i:s'),
            'date_arrivee'   => Carbon::now()->addDays(2)->toDateString(),
            'heure_arrivee'  => Carbon::now()->addDays(2)->addHours(4)->format('H:i:s'),
            'statut'         => 'ouvert',
            'ecologique'     => false,
        ]);

        // ✅ Troisième covoiturage
        Covoiturage::create([
            'utilisateur_id' => $user->id,
            'voiture_id'     => $voiture->id,
            'nb_place'       => 1,
            'prix_personne'  => 10.00,
            'lieu_depart'    => 'Marseille',
            'lieu_arrivee'   => 'Nice',
            'date_depart'    => Carbon::now()->addDays(3)->toDateString(),
            'heure_depart'   => Carbon::now()->addDays(3)->format('H:i:s'),
            'date_arrivee'   => Carbon::now()->addDays(3)->toDateString(),
            'heure_arrivee'  => Carbon::now()->addDays(3)->addHours(2)->format('H:i:s'),
            'statut'         => 'ouvert',
            'ecologique'     => true,
        ]);
    }
}


