<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Covoiturage;
use App\Models\Reservation;
use App\Mail\ReservationAnnuleeParChauffeur;
use App\Mail\ReservationAnnuleeParPassager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $dejaReserve = Reservation::where('utilisateur_id', $user->id)
            ->where('covoiturage_id', $id)
            ->exists();

        if ($dejaReserve) {
            return back()->with('error', 'Vous avez déjà réservé ce covoiturage.');
        }


            if ($covoiturage->nb_place <= 0) {
                return back()->with('error', 'Plus de places disponibles pour ce trajet.');
            }

            if ($user->credit < $covoiturage->prix_personne) {
                return back()->with('error', 'Crédits insuffisants pour réserver ce covoiturage.');
            }

            try {
                DB::transaction(function () use ($user, $covoiturage) {
                Reservation::create([
                    'utilisateur_id' => $user->id,
                    'covoiturage_id' => $covoiturage->id,
                    'date_reservation' => now(),
                    'statut' => 'confirmée',
                ]);

                $user->decrement('credit', $covoiturage->prix_personne);
                $covoiturage->decrement('nb_place');
            });

            return back()->with('success', 'Réservation effectuée avec succès ✅');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la réservation. Veuillez réessayer.');
        }
    }
    public function annuler(Covoiturage $covoiturage)
        {
        $user = Auth::user();

        if (!$covoiturage->peutAnnuler()) {
            return back()->with('error', 'Vous ne pouvez pas annuler ce covoiturage.');
        }

        try {
            DB::transaction(function () use ($covoiturage, $user) {

                // ===== Chauffeur annule son trajet
                if ($covoiturage->utilisateur_id === $user->id) {

                    foreach ($covoiturage->reservations as $reservation) {
                        $participant = $reservation->utilisateur;

                        // Remboursement crédits
                        $participant->credit += $covoiturage->prix_personne;
                        $participant->save();

                        // Envoi mail au participant
                        Mail::to($participant->email)
                            ->send(new ReservationAnnuleeParChauffeur($covoiturage));
                    }

                    // Changer le statut du covoiturage
                    $covoiturage->statut = 'annule';
                    $covoiturage->save();

                    return;
                }

                // ===== Passager annule sa réservation
                $reservation = $covoiturage->reservations()
                    ->where('utilisateur_id', $user->id)
                    ->firstOrFail();

                // Changer le statut au lieu de supprimer
                $reservation->statut = 'annule';
                $reservation->save();

                // Remboursement crédits
                $user->credit += $covoiturage->prix_personne;
                $user->save();

                // Libérer une place
                $covoiturage->nb_place += 1;
                $covoiturage->save();

                // Envoi mail au chauffeur
                Mail::to($covoiturage->chauffeur->email)
                    ->send(new ReservationAnnuleeParPassager($covoiturage));

            });

            return back()->with('success', 'Annulation effectuée ✅');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l’annulation : ' . $e->getMessage());
        }
    }
}