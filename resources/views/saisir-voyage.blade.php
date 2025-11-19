@extends('layouts.app')

@section('title', 'Saisir un voyage - EcoRide')

@section('content')

@vite('resources/js/saisir-un-voyage.js')

<div class="container py-5 mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white py-3">
            <h2 class="h4 mb-0"><i class="bi bi-geo-alt"></i> Saisir un nouveau voyage</h2>
        </div>

        <div class="card-body p-4">

            {{-- Messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="form-voyage" action="{{ route('voyages.store') }}" method="POST">
                @csrf

                {{-- Sélection véhicule existant --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-car-front"></i> Sélectionner un véhicule</h5>
                    <select name="voiture_id" class="form-select">
                        <option value="">-- Choisir un véhicule --</option>
                        @foreach($voitures as $voiture)
                            <option value="{{ $voiture->id }}">
                                {{ $voiture->marque }} {{ $voiture->modele }} ({{ $voiture->immatriculation }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                {{-- Nouveau véhicule (facultatif) --}}
                <div class="mb-4 bg-light border rounded-3 p-4 shadow-sm">
                    <h5 class="fw-bold mb-3"><i class="bi bi-car-front"></i> Ajouter un nouveau véhicule (facultatif)</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Plaque d’immatriculation</label>
                            <input type="text" name="vehicule[0][immatriculation]" class="form-control" placeholder="Ex: AB-123-CD">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de première immatriculation</label>
                            <input type="date" name="vehicule[0][date_premiere_immatriculation]" class="form-control" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marque</label>
                            <input type="text" name="vehicule[0][marque]" class="form-control" placeholder="Ex: Renault" >
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Modèle</label>
                            <input type="text" name="vehicule[0][modele]" class="form-control" placeholder="Ex: Clio" >
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Couleur</label>
                            <input type="text" name="vehicule[0][couleur]" class="form-control" placeholder="Ex: Rouge" >
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Places disponibles</label>
                            <input type="number" name="vehicule[0][places_disponibles]" class="form-control" min="1" max="7" value="1" >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Énergie</label>
                            <select name="vehicule[0][energie]" class="form-select">
                                <option value="">--</option>
                                <option value="Essence">Essence</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Hybride">Hybride</option>
                                <option value="Électrique">Électrique</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-gear"></i> Préférences</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="vehicule[0][preferences][fumeur]" value="1" id="fumeur-0">
                            <label class="form-check-label" for="fumeur-0">J’accepte les fumeurs</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="vehicule[0][preferences][animal]" value="1" id="animal-0">
                            <label class="form-check-label" for="animal-0">J’accepte les animaux</label>
                        </div>

                        <div class="d-flex gap-2 mb-2 mt-2">
                            <input type="text" id="prefPersonnalise-0" name="vehicule[0][preferences][custom][]" class="form-control" placeholder="Ex : Musique autorisée">
                            <button type="button" class="btn btn-outline-primary btn-sm ajouterPref" data-id="0"><i class="bi bi-plus"></i> Ajouter</button>
                        </div>
                        <div id="listePrefPersonnalise-0"></div>
                    </div>
                </div>

                <hr>

                {{-- Informations sur le voyage --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-fill"></i> Informations sur le voyage</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lieu de départ</label>
                            <input type="text" name="lieu_depart" class="form-control" placeholder="Ex: Paris" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lieu d’arrivée</label>
                            <input type="text" name="lieu_arrivee" class="form-control" placeholder="Ex: Canne" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de départ</label>
                            <input type="date" name="date_depart" class="form-control" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Heure de départ</label>
                            <input type="time" name="heure_depart" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prix par personne</label>
                            <input type="number" name="prix_personne" class="form-control" placeholder="Ex: 15" min="2" required>
                        </div>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="text-end mt-4">
                    <a href="{{ route('mon-espace') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Enregistrer le voyage
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection