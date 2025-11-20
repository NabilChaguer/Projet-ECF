<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Covoiturage;
use App\Models\Reservation;
use App\Models\Avis;
use App\Mail\ReservationAnnuleeParChauffeur;
use App\Mail\ReservationAnnuleeParPassager;
use App\Mail\FinCovoiturageParticipants;
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
            return back()->with('error', 'Une erreur est survenue lors de la réservation.');
        }
    }

    /**
     * Chauffeur : démarrer le covoiturage
     */
    public function demarrerCovoiturage($id)
    {
        $voyage = Covoiturage::findOrFail($id);

        if ($voyage->utilisateur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à démarrer ce covoiturage.');
        }

        if ($voyage->statut !== 'ouvert') {
            return back()->with('error', 'Ce covoiturage ne peut pas être démarré.');
        }

        $voyage->statut = 'en_cours';
        $voyage->save();

        return back()->with('success', 'Le covoiturage a démarré 🚗💨');
    }

    /**
     * Chauffeur : clore le covoiturage
     */
   public function cloreCovoiturage($id)
    {
        $voyage = Covoiturage::with('reservations.utilisateur')->findOrFail($id);

        if ($voyage->utilisateur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à clore ce covoiturage.');
        }

        if ($voyage->statut !== 'en_cours') {
            return back()->with('error', 'Ce covoiturage ne peut pas être clôturé.');
        }

        // Passage en terminé
        $voyage->statut = 'termine';
        $voyage->save();

        // Envoi d’un mail à tous les participants
        foreach ($voyage->reservations as $reservation) {

            $participant = $reservation->utilisateur;

            if ($participant && $participant->email) {
                Mail::to($participant->email)
                    ->send(new FinCovoiturageParticipants($reservation));
            }
        }

        return back()->with('success', 'Le covoiturage est maintenant terminé 🎉');
    }

    /**
     * Annulation par chauffeur ou passager
     */
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

    public function validerPassager($id, Request $request)
    {
        $user = Auth::user();

        // Trouver la réservation correspondante au passager
        $reservation = Reservation::where('covoiturage_id', $id)
            ->where('utilisateur_id', $user->id)
            ->firstOrFail();

        $action = $request->input('action');
        $note = $request->input('note');
        $commentaire = $request->input('commentaire');

        // Mettre à jour la validation passager
        $reservation->validation_passager = $action;
        $reservation->save();

        // Créer l'avis si note/commentaire renseignés
        if ($note || $commentaire) {
            \App\Models\Avis::create([
                'utilisateur_id' => $user->id,
                'covoiturage_id' => $id,
                'note' => $note ?: null,
                'commentaire' => $commentaire ?: null,
                'status' => 'en_attente',
            ]);
        }

        // Si tout est OK, créditer le chauffeur
        if ($action === 'ok') {
            $chauffeur = $reservation->covoiturage->chauffeur;
            if ($chauffeur) {
                $chauffeur->increment('credit', $reservation->covoiturage->prix_personne);
            }
        }

        return back()->with('success', 'Votre validation a été enregistrée ✅');
    }

}
