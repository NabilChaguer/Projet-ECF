<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonEspaceController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $voitures = Voiture::where('utilisateur_id', $user->id)->get();

        return view('mon-espace', compact('user', 'voitures'));
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

    // Supprimer un véhicule --}}
    public function destroy($id)
    {
        $voiture = Voiture::where('id', $id)
            ->where('utilisateur_id', Auth::id())
            ->firstOrFail();

        $voiture->delete();

        return redirect()->route('mon-espace')->with('success', 'Véhicule supprimé avec succès.');
    }
}

