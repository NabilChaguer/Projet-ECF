<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Covoiturage;
use Illuminate\Support\Facades\DB;

class CovoiturageController extends Controller
{
    // Page principale
    public function index(Request $request)
    {
        $searchActive = session()->has('covoiturages');

        $covoiturages  = $searchActive ? session('covoiturages', collect()) : collect();
        $alternatives  = $searchActive ? session('alternatives', collect()) : collect();
        $raison        = $searchActive ? session('raison', null) : null;
        $filters       = $searchActive ? session('filters', []) : [];

        return view('covoiturage', compact(
            'covoiturages',
            'alternatives',
            'raison',
            'filters',
            'searchActive'
        ));
    }

    // Recherche + filtres
    public function search(Request $request)
    {
        $request->validate([
            'departure'  => 'required|string|max:255',
            'arrival'    => 'required|string|max:255',
            'date'       => 'nullable|date',
            'prix_max'   => 'nullable|numeric|min:0',
            'duree_max'  => 'nullable|numeric|min:1',
            'note_min'   => 'nullable|numeric|min:0|max:5',
            'ecologique' => 'nullable|in:0,1',
        ]);

        $baseQuery = Covoiturage::query()
            ->where('nb_place', '>', 0)
            ->whereRaw('LOWER(lieu_depart) LIKE ?', ['%' . mb_strtolower($request->departure) . '%'])
            ->whereRaw('LOWER(lieu_arrivee) LIKE ?', ['%' . mb_strtolower($request->arrival) . '%']);

        if ($request->filled('date')) {
            $baseQuery->whereDate('date_depart', $request->date);
        }

        $covoituragesAvantFiltres = (clone $baseQuery)
            ->with(['voiture.utilisateur', 'avis'])
            ->get();

        $query = (clone $baseQuery);

        if ($request->filled('prix_max')) {
            $query->where('prix_personne', '<=', $request->prix_max);
        }

        if ($request->filled('duree_max')) {
            $query->whereRaw("
                TIMESTAMPDIFF(
                    MINUTE,
                    CONCAT(date_depart, ' ', heure_depart),
                    CONCAT(date_arrivee, ' ', heure_arrivee)
                ) <= ?
            ", [$request->duree_max]);
        }

        if ($request->filled('note_min')) {
            $noteMin = (float) $request->note_min;

            $idsWithAvg = DB::table('avis')
                ->select('covoiturage_id', DB::raw('AVG(note) as avg_note'))
                ->groupBy('covoiturage_id')
                ->havingRaw('AVG(note) >= ?', [$noteMin])
                ->pluck('covoiturage_id')
                ->toArray();

            if (!empty($idsWithAvg)) {
                $query->whereIn('id', $idsWithAvg);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        if ($request->filled('ecologique') && $request->ecologique == '1') {
            $query->where('ecologique', true);
        }

        $covoiturages = $query->with(['voiture.utilisateur', 'avis'])->get();

        $alternatives = collect();
        $raison = null;

        if ($covoituragesAvantFiltres->isEmpty()) {
            $raison = 'aucun_resultat';
        } elseif ($covoiturages->isEmpty()) {
            $raison = 'filtres';
            $alternatives = $covoituragesAvantFiltres->sortBy('date_depart')->take(3);
        }

        return redirect()->route('covoiturages.index')->with([
            'covoiturages' => $covoiturages,
            'alternatives' => $alternatives,
            'raison'       => $raison,
            'filters'      => $request->only([
                'departure', 'arrival', 'date',
                'prix_max', 'duree_max', 'note_min', 'ecologique'
            ]),
        ]);
    }
}


