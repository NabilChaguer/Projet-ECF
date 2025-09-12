<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Covoiturage;

class CovoiturageController extends Controller
{
    public function index()
    {
        // Au départ, aucune recherche, on envoie des collections vides
        $covoiturages = collect();
        $alternatives = collect();

        return view('covoiturage', compact('covoiturages', 'alternatives'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'departure' => 'required|string',
            'arrival'   => 'required|string',
            'date'      => 'nullable|date',
        ]);

        // Recherche de covoiturages avec places disponibles
        $query = Covoiturage::query()->where('nb_place', '>', 0);

        // Recherche flexible (insensible à la casse)
        $query->whereRaw('LOWER(lieu_depart) LIKE ?', ['%' . mb_strtolower($request->departure) . '%'])
              ->whereRaw('LOWER(lieu_arrivee) LIKE ?', ['%' . mb_strtolower($request->arrival) . '%']);

        // Filtre sur la date exacte si fournie
        if ($request->filled('date')) {
            $query->whereDate('date_depart', $request->date);
        }

        $covoiturages = $query->with(['voiture.utilisateur', 'avis'])->get();
        $alternatives = collect();

        // ======= SI PAS DE RESULTATS EXACTS =======
        if ($covoiturages->isEmpty()) {

            $alternativesQuery = Covoiturage::where('nb_place', '>', 0)
                ->whereRaw('LOWER(lieu_depart) LIKE ?', ['%' . mb_strtolower($request->departure) . '%'])
                ->whereRaw('LOWER(lieu_arrivee) LIKE ?', ['%' . mb_strtolower($request->arrival) . '%']);

            $alternatives = $alternativesQuery
                ->with(['voiture.utilisateur', 'avis'])
                ->orderBy('date_depart', 'asc')
                ->take(3)
                ->get();

            // Si toujours vide, proposer les 3 prochains trajets liés aux bonnes villes
            if ($alternatives->isEmpty()) {
                $alternatives = Covoiturage::where('nb_place', '>', 0)
                    ->whereRaw('LOWER(lieu_depart) LIKE ?', ['%' . mb_strtolower($request->departure) . '%'])
                    ->whereRaw('LOWER(lieu_arrivee) LIKE ?', ['%' . mb_strtolower($request->arrival) . '%'])
                    ->with(['voiture.utilisateur', 'avis'])
                    ->orderBy('date_depart', 'asc')
                    ->take(3)
                    ->get();
            }
        }

        return view('covoiturage', compact('covoiturages', 'alternatives'));

    }
}

