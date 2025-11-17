@extends('layouts.app')

@section('title', 'Mon espace - EcoRide')

@section('content')
@php
use Carbon\Carbon;

$rolesUtilisateur = $user->roles->pluck('libelle')->map(fn($l) => strtolower($l))->toArray();
$roleInitial = in_array('chauffeur', $rolesUtilisateur) && in_array('passager', $rolesUtilisateur)
    ? 'les-deux'
    : (in_array('chauffeur', $rolesUtilisateur)
        ? 'chauffeur'
        : (in_array('passager', $rolesUtilisateur) ? 'passager' : ''));
@endphp

<div class="container py-5 mt-5" x-data="{ roleSelectionne: '{{ $roleInitial }}' }">

    {{-- Message succès / erreur --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white py-3">
            <h2 class="h4 mb-0"><i class="bi bi-person-circle"></i> Mon espace personnel</h2>
        </div>

        <div class="card-body p-4">
            <p class="text-muted mb-4">Bienvenue <strong>{{ $user->pseudo }}</strong> 👋</p>

            {{------------ CHOIX DU ROLE ------------}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-badge"></i> Choisissez votre rôle :</h5>
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-outline-success" :class="{ 'active': roleSelectionne === 'chauffeur' }" @click="roleSelectionne = 'chauffeur'">Chauffeur</button>
                    <button type="button" class="btn btn-outline-success" :class="{ 'active': roleSelectionne === 'passager' }" @click="roleSelectionne = 'passager'">Passager</button>
                    <button type="button" class="btn btn-outline-success" :class="{ 'active': roleSelectionne === 'les-deux' }" @click="roleSelectionne = 'les-deux'">Les deux</button>
                </div>
                <small class="text-muted d-block mt-2">Sélectionnez un rôle pour afficher le(s) formulaire(s) correspondant(s).</small>
            </div>

            {{------------ FORMULAIRE PASSAGER (recherche) ------------}}
            <div x-show="roleSelectionne === 'passager' || roleSelectionne === 'les-deux'" x-transition class="mt-5">
                <h5 class="fw-bold mb-3"><i class="bi bi-search"></i> Rechercher un covoiturage</h5>
                <form id="formRecherche" action="{{ route('covoiturages.search') }}" method="POST" class="bg-white rounded-4 shadow p-4 p-md-5 mx-auto" style="max-width:1000px;">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Départ</label>
                            <input type="text" name="departure" class="form-control" placeholder="Ville de départ" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Arrivée</label>
                            <input type="text" name="arrival" class="form-control" placeholder="Ville d'arrivée" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn w-100 text-white border-0 btn-color">
                                <i class="bi bi-search me-1"></i> Rechercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{------------ FORMULAIRE AJOUT VEHICULE ------------}}
            <form method="POST" action="{{ route('mon-espace.update') }}"
                  x-cloak x-show="roleSelectionne === 'chauffeur' || roleSelectionne === 'les-deux'" x-transition id="formAjouterVehicule">
                @csrf
                <input type="hidden" name="role" :value="roleSelectionne">

            {{------------ BOUTON SAISIR UN VOYAGE ------------}}
            <div class="mt-4" x-show="roleSelectionne === 'chauffeur' || roleSelectionne === 'les-deux'" x-transition>
                <a href="{{ route('voyages.create') }}"
                class="btn btn-primary px-4 py-2 shadow">
                    <i class="bi bi-plus-circle"></i> Saisir un nouveau voyage
                </a>
            </div>

                <div class="mt-3 bg-light border rounded-3 p-4 shadow-sm">
                    <h5 class="fw-bold mb-3"><i class="bi bi-car-front"></i> Ajouter un véhicule</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Plaque d’immatriculation</label>
                            <input type="text" name="vehicule[0][immatriculation]" class="form-control" placeholder="Ex: AB-123-CD" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de première immatriculation</label>
                            <input type="date" name="vehicule[0][date_premiere_immatriculation]" class="form-control" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marque</label>
                            <input type="text" name="vehicule[0][marque]" class="form-control" placeholder="Ex: Renault" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Modèle</label>
                            <input type="text" name="vehicule[0][modele]" class="form-control" placeholder="Ex: Clio" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Couleur</label>
                            <input type="text" name="vehicule[0][couleur]" class="form-control" placeholder="Ex: Rouge" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Places disponibles</label>
                            <input type="number" name="vehicule[0][places_disponibles]" class="form-control" min="1" max="7" value="1" required>
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

                    {{-- Préférences lors de l'ajout --}}
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

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-check-circle"></i> Ajouter le véhicule</button>
                    </div>
                </div>
            </form>

            {{------------ VEHICULES ENREGISTRES ------------}}
            @if($user->voitures->count())
                <div class="mt-5">
                    <h5 class="fw-bold mb-3"><i class="bi bi-card-list"></i> Vos véhicules enregistrés</h5>
                    <div class="row g-3">
                        @foreach($user->voitures as $voiture)
                            <div class="col-md-6">
                                <div class="border rounded p-3 bg-white shadow-sm position-relative">
                                    <h6 class="mb-1"><strong>Marque :</strong> {{ $voiture->marque }}</h6>
                                    <h6 class="mb-1"><strong>Modele :</strong> {{ $voiture->modele }}</h6>
                                    <p class="mb-1"><strong>Immatriculation :</strong> {{ $voiture->immatriculation }}</p>
                                    <p class="mb-1"><strong>Date première immatriculation :</strong> {{ Carbon::parse($voiture->date_premiere_immatriculation)->format('d/m/Y') }}</p>
                                    <p class="mb-1"><strong>Couleur :</strong> {{ $voiture->couleur ?? '—' }}</p>
                                    <p class="mb-1"><strong>Places disponibles :</strong> {{ $voiture->places_disponibles ?? 1 }}</p>
                                    
                                    @if($voiture->ecologique)
                                        <p><strong>Écologique :</strong> Oui 🌱</p>
                                    @else
                                        <p><strong>Écologique :</strong> Non 🚗</p>
                                    @endif

                                    @if(!empty($voiture->preferences))
                                        <div class="mt-3 p-2 bg-light border rounded">
                                            <h6 class="fw-bold mb-2"><i class="bi bi-stars"></i> Préférences enregistrées :</h6>
                                            <ul class="list-unstyled mb-0">
                                                <li>
                                                    <i class="bi {{ ($voiture->preferences['fumeur'] ?? false) ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                                    <strong>Fumeur :</strong> {{ ($voiture->preferences['fumeur'] ?? false) ? 'Oui' : 'Non' }}
                                                </li>
                                                <li>
                                                    <i class="bi {{ ($voiture->preferences['animal'] ?? false) ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                                    <strong>Animaux :</strong> {{ ($voiture->preferences['animal'] ?? false) ? 'Acceptés' : 'Non acceptés' }}
                                                </li>
                                                @if(!empty($voiture->preferences['custom']))
                                                    <li class="mt-2">
                                                        <strong>Autres préférences :</strong>
                                                        <ul class="mb-0">
                                                            @foreach($voiture->preferences['custom'] as $pref)
                                                                <li> {{ $pref }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif

                                    {{-- Suppression du véhicule (modale) --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal"
                                            data-id="{{ $voiture->id }}"
                                            data-marque="{{ $voiture->marque }}"
                                            data-modele="{{ $voiture->modele }}">
                                        <i class="bi bi-trash"></i> Supprimer ce véhicule
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{------------ VOS VOYAGES ENREGISTRÉS ------------}}
            @if($voyages->count())
                <hr class="my-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-fill"></i> Vos voyages enregistrés</h5>

                <div class="list-group">
                    @foreach($voyages as $v)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $v->lieu_depart }} → {{ $v->lieu_arrivee }}</strong><br>
                                Date : {{ \Carbon\Carbon::parse($v->date_depart)->format('d/m/Y') }} {{ $v->heure_depart ?? '' }} <br>
                                Véhicule :
                                {{ $v->voiture->marque ?? '—' }}
                                {{ $v->voiture->modele ?? '' }}
                                ({{ $v->voiture->immatriculation ?? '' }})
                            </div>

                            <span class="fw-bold">{{ $v->prix_personne }} crédits / {{ $v->nb_place }} places</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{------------ MODALE DE CONFIRMATION SUPPRESSION ------------}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteLabel"><i class="bi bi-exclamation-triangle"></i> Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer ce véhicule ?</p>
                <p class="text-danger mt-2"><small>Cette action est irréversible. Tout ce qui est lié à ce véhicule sera également supprimé
                    (trajets, historiques, données associées, etc.).</small></p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ========================= SCRIPTS ========================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Reset du formulaire véhicule après ajout
    const formVehicule = document.getElementById('formAjouterVehicule');
    formVehicule?.addEventListener('submit', () => setTimeout(() => formVehicule.reset(), 500));

    // Gestion des préférences personnalisées
    document.querySelectorAll('.ajouterPref').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.getElementById(`prefPersonnalise-${id}`);
            const liste = document.getElementById(`listePrefPersonnalise-${id}`);
            const valeur = input.value.trim();
            if (!valeur) return;

            const div = document.createElement('div');
            div.className = 'd-flex align-items-center mb-2 bg-light border rounded p-2';
            div.innerHTML = `
                <input type="hidden" name="vehicule[0][preferences][custom][]" value="${valeur}">
                <span class="me-auto">${valeur}</span>
                <button type="button" class="btn btn-sm btn-outline-danger supprimer-pref"><i class="bi bi-x-circle"></i></button>
            `;
            liste.appendChild(div);
            input.value = '';
            div.querySelector('.supprimer-pref').addEventListener('click', () => div.remove());
        });
    });

    // Gestion de la modale de suppression
    const modal = document.getElementById('confirmDeleteModal');
    const deleteForm = document.getElementById('deleteForm');

    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');

        deleteForm.action = `/voitures/${id}`;
    });
});
</script>
@endsection