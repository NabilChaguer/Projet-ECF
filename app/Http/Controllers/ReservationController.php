<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Covoiturage;
use App\Models\Reservation;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function store($id)
    {
        
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Veuillez vous connecter pour réserver un covoiturage.');
        }

        /** @var \App\Models\Utilisateur $user */
        $user = Auth::user();
        $covoiturage = Covoiturage::findOrFail($id);

        if ($covoiturage->nb_place <= 0) {
            return back()->with('error', 'Plus de places disponibles pour ce trajet.');
        }

        if ($user->credit < $covoiturage->prix_personne) {
            return back()->with('error', 'Crédits insuffisants pour réserver ce covoiturage.');
        }

        $dejaReserve = Reservation::where('utilisateur_id', $user->id)
            ->where('covoiturage_id', $id)
            ->exists();

        if ($dejaReserve) {
            return back()->with('error', 'Vous avez déjà réservé ce covoiturage.');
        }

        DB::transaction(function () use ($user, $covoiturage, $id) {
            Reservation::create([
                'covoiturage_id' => $id,
                'utilisateur_id' => $user->id,
                'statut' => 'confirmée',
            ]);

            $user->decrement('credit', $covoiturage->prix_personne);
            $covoiturage->decrement('nb_place');
        });

        return back()->with('success', 'Réservation effectuée avec succès ✅');
    }
}


