<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use App\Models\Covoiturage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonEspaceController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Voitures du user
        $voitures = Voiture::where('utilisateur_id', $user->id)->get();

        // Chauffeur - trajets actifs
        $voyagesChauffeurActifs = Covoiturage::where('utilisateur_id', $user->id)
            ->where('statut', '!=', 'annule')
            ->with('voiture')
            ->latest()
            ->get();

        // Chauffeur - trajets annulés
        $voyagesChauffeurAnnules = Covoiturage::where('utilisateur_id', $user->id)
            ->where('statut', 'annule')
            ->with('voiture')
            ->latest()
            ->get();

        // Passager - trajets actifs
        $voyagesPassagerActifs = Covoiturage::whereHas('reservations', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id)
                    ->where('statut', 'confirmée'); // uniquement réservations confirmées
            })
            ->where('statut', '!=', 'annule')
            ->with('voiture')
            ->latest()
            ->get();

        // Passager - trajets annulés
        $voyagesPassagerAnnules = Covoiturage::whereHas('reservations', function ($q) use ($user) {
                $q->where('utilisateur_id', $user->id)
                    ->where('statut', 'annule'); // uniquement réservations annulées
            })
            ->with('voiture')
            ->latest()
            ->get();

        return view('mon-espace', compact(
            'user',
            'voitures',
            'voyagesChauffeurActifs',
            'voyagesChauffeurAnnules',
            'voyagesPassagerActifs',
            'voyagesPassagerAnnules'
        ));
    }

    // --- Suppression définitive d’un covoiturage ou d’une réservation ---}}
    public function supprimerDefinitif($id)
    {
        $user = Auth::user();
        $voyage = Covoiturage::with('reservations')->findOrFail($id);

        if ($voyage->utilisateur_id === $user->id) {
            // Chauffeur → peut supprimer définitivement le covoiturage
            $voyage->delete();
            return back()->with('success', 'Trajet supprimé définitivement.');
        }

        // Passager → supprimer seulement sa réservation
        $reservation = $voyage->reservations()
            ->where('utilisateur_id', $user->id)
            ->first();

        if ($reservation) {
            $reservation->delete(); // ou mettre 'statut' = 'supprime'
            return back()->with('success', 'Réservation supprimée de votre vue.');
        }

        return back()->with('error', 'Action non autorisée.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // --- Mise à jour des préférences d’un véhicule existant ---}}
        if ($request->filled('voiture_id')) {
            $voiture = Voiture::where('id', $request->voiture_id)
                ->where('utilisateur_id', $user->id)
                ->firstOrFail();

            $voiture->update([
                'preferences' => [
                    'fumeur' => $request->boolean('preferences.fumeur'),
                    'animal' => $request->boolean('preferences.animal'),
                    'custom' => array_filter($request->input('preferences.custom', [])),
                ],
            ]);

            return back()->with('success', 'Préférences enregistrées avec succès ✅');
        }

        // --- Ajout ou mise à jour d’un véhicule ---}}
        if ($request->has('vehicule')) {
            foreach ($request->vehicule as $data) {
                Voiture::updateOrCreate(
                    [
                        'immatriculation' => $data['immatriculation'],
                        'utilisateur_id' => $user->id,
                    ],
                    [
                        'marque' => $data['marque'],
                        'modele' => $data['modele'],
                        'energie' => $data['energie'] ?? 'Non précisé',
                        'couleur' => $data['couleur'] ?? null,
                        'places_disponibles' => (int)($data['places_disponibles'] ?? 1),
                        'date_premiere_immatriculation' => $data['date_premiere_immatriculation'],
                        'preferences' => [
                            'fumeur' => !empty($data['preferences']['fumeur']),
                            'animal' => !empty($data['preferences']['animal']),
                            'custom' => array_filter($data['preferences']['custom'] ?? []),
                        ],
                    ]
                );
            }

            return back()->with('success', 'Véhicule ajouté avec succès');
        }

        return back()->with('error', 'Aucune action détectée.');
    }

    // --- Supprimer un véhicule ---}}
    public function destroy($id)
    {
        $voiture = Voiture::where('id', $id)
            ->where('utilisateur_id', Auth::id())
            ->firstOrFail();

        $voiture->delete();

        return redirect()->route('mon-espace')->with('success', 'Véhicule supprimé avec succès.');
    }

    public function saisirVoyage()
    {
        $user = Auth::user();

        $voitures = $user->voitures()->get();
        $voyages = $user->covoiturages()->with('voiture')->latest()->get();

        return view('saisir-voyage', compact('voitures', 'voyages'));
    }

    public function storeVoyage(Request $request)
    {
        $user = Auth::user();

        // --- Validation ---}}
        $validated = $request->validate([
            'voiture_id' => 'nullable|exists:voitures,id',
            'vehicule.0.marque' => 'nullable|string|max:255',
            'vehicule.0.modele' => 'nullable|string|max:255',
            'vehicule.0.immatriculation' => 'nullable|string|max:255',
            'vehicule.0.couleur' => 'nullable|string|max:255',
            'vehicule.0.energie' => 'nullable|string|max:50',
            'vehicule.0.date_premiere_immatriculation' => 'nullable|date',
            'vehicule.0.places_disponibles' => 'nullable|integer|min:1|max:9',
            'lieu_depart' => 'required|string|max:255',
            'lieu_arrivee' => 'required|string|max:255',
            'date_depart' => 'required|date',
            'heure_depart' => 'required',
            'prix_personne' => 'nullable|numeric|min:0',
        ]);

        $prixFinal = max(0, ($request->prix_personne ?? 0) - 2);

        $voitureId = null;
        $nv = $request->input('vehicule')[0] ?? null;

        if (!empty($nv['marque'])) {

            // --- Création voiture avec préférences ---}}
            $voiture = Voiture::create([
                'utilisateur_id' => $user->id,
                'marque' => $nv['marque'],
                'modele' => $nv['modele'] ?? null,
                'immatriculation' => $nv['immatriculation'] ?? null,
                'couleur' => $nv['couleur'] ?? null,
                'energie' => $nv['energie'] ?? 'Non spécifiée',
                'date_premiere_immatriculation' => $nv['date_premiere_immatriculation'] ?? null,
                'places_disponibles' => (int)($nv['places_disponibles'] ?? 1),
                'preferences' => [
                    'fumeur' => !empty($nv['preferences']['fumeur']),
                    'animal' => !empty($nv['preferences']['animal']),
                    'custom' => array_filter($nv['preferences']['custom'] ?? []),
                ],
            ]);

            $voitureId = $voiture->id;

        } elseif (!empty($validated['voiture_id'])) {

            $voiture = Voiture::findOrFail($validated['voiture_id']);
            $voitureId = $voiture->id;
        }

        if (!$voitureId) {
            return back()
                ->withErrors(['voiture_id' => 'Veuillez sélectionner ou créer un véhicule.'])
                ->withInput();
        }

        // --- Détection écologique à partir de l’énergie ---}}
        $energie = strtolower(str_replace(['é','è','ê'], 'e', $voiture->energie));
        $isEcologique = in_array($energie, ['hybride', 'electrique']) ? 1 : 0;

        // --- Création du covoiturage ---}}
        Covoiturage::create([
            'utilisateur_id' => $user->id,
            'voiture_id' => $voitureId,
            'lieu_depart' => $validated['lieu_depart'],
            'lieu_arrivee' => $validated['lieu_arrivee'],
            'date_depart' => $validated['date_depart'],
            'heure_depart' => $validated['heure_depart'],
            'date_arrivee' => $validated['date_depart'],
            'heure_arrivee' => $validated['heure_depart'],
            'prix_personne' => $prixFinal,
            'nb_place' => $voiture->places_disponibles ?? 1,
            'statut' => 'ouvert',
            'ecologique' => $isEcologique,
        ]);

        return redirect()->route('mon-espace')
            ->with('success', 'Voyage enregistré avec succès ✅');
    }
}

