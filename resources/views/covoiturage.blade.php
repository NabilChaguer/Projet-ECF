@extends('layouts.app')

@section('title', 'EcoRide - Covoiturage')

@section('content')

<main class="flex-grow-1 d-flex justify-content-center bg-gradient-section h-100">
<section class="container">
    
    {{-- Formulaire principal --}}
    <div class="mb-4 text-center mt-8rem">
      <h1 class="fw-bold display-6">Trouvez votre covoiturage idéal</h1>
      <p class="text-muted">Recherchez des trajets disponibles et voyagez durablement</p>
    </div>

    <form action="{{ route('covoiturages.search') }}" method="POST" class="bg-white rounded-4 shadow p-4 p-md-5 mx-auto" style="max-width: 1000px;">
    @csrf
      <div class="row g-3 align-items-end">

        {{-- Départ --}}
        <div class="col-12 col-md-3">
          <label for="departure" class="form-label">Départ</label>
          <div class="input-group">
            <span class="input-group-text border-end-0">
                <i class="bi bi-geo-alt-fill"></i>
            </span>
            <input type="text" id="departure" name="departure" class="form-control" placeholder="Ville de départ" required>
          </div>
        </div>

        {{-- Arrivée --}}
        <div class="col-12 col-md-3">
          <label for="arrival" class="form-label">Arrivée</label>
          <div class="input-group">
            <span class="input-group-text border-end-0">
                <i class="bi bi-geo-alt-fill"></i>
            </span>
            <input type="text" id="arrival" name="arrival" class="form-control" placeholder="Ville d'arrivée" required>
          </div>
        </div>

        {{-- Date --}}
        <div class="col-12 col-md-3">
            <label for="date" class="form-label">Date</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-calendar-check"></i>
                </span>
                <input type="date" id="date" name="date" class="form-control" min="{{ date('Y-m-d') }}" placeholder="Choisir une date" required>
            </div>
        </div>

        {{-- Bouton --}}
        <div class="col-12 col-md-3">
          <button type="submit" class="btn w-100 text-white border-0 btn-color">
            <span class="me-2 d-inline-flex align-items-center">Rechercher</span>
          </button>
        </div>
        
      </div>
    </form>

    {{-- Filtres --}}
    @if($covoiturages->isNotEmpty())
        <div class="card shadow-sm border-0 rounded-4 mt-5">
            <div class="card-header bg-light rounded-top-4">
                <h5 class="mb-0 fw-bold text-center">
                    <i class="bi bi-funnel-fill me-2"></i> Affiner votre recherche
                </h5>
            </div>

            <div class="card-body">
                <form action="{{ route('covoiturages.search') }}" method="POST">
                    @csrf
                    <input type="hidden" name="departure" value="{{ $filters['departure'] ?? '' }}">
                    <input type="hidden" name="arrival"   value="{{ $filters['arrival'] ?? '' }}">
                    <input type="hidden" name="date"      value="{{ $filters['date'] ?? '' }}">

                    <div class="row g-4">
                        <div class="col-md-3">
                            <label for="prix_max" class="form-label fw-semibold">Prix maximum (€)</label>
                            <input type="number" id="prix_max" name="prix_max" class="form-control shadow-sm"
                                value="{{ $filters['prix_max'] ?? '' }}" placeholder="Ex: 15">
                        </div>

                        <div class="col-md-3">
                            <label for="duree_max" class="form-label fw-semibold">Durée maximum (min)</label>
                            <input type="number" id="duree_max" name="duree_max" class="form-control shadow-sm"
                                value="{{ $filters['duree_max'] ?? '' }}" placeholder="Ex: 120">
                        </div>

                        <div class="col-md-3">
                            <label for="note_min" class="form-label fw-semibold">Note minimale</label>
                            <input type="number" id="note_min" name="note_min" class="form-control shadow-sm"
                                min="0" max="5" step="0.1" value="{{ $filters['note_min'] ?? '' }}" placeholder="Ex: 4">
                        </div>

                        <div class="col-md-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input type="checkbox" id="ecologique" name="ecologique" value="1"
                                    class="form-check-input" {{ !empty($filters['ecologique']) ? 'checked' : '' }}>
                                <label for="ecologique" class="form-check-label fw-semibold">Voyage écologique 🌱</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-4 me-2 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Appliquer les filtres
                        </button>
                        <a href="{{ route('covoiturages.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                            <i class="bi bi-arrow-repeat me-1"></i> Nouvelle recherche
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="container mt-4">

        {{-- Messages de retour --}}
        @if(session('success'))
            <div class="alert alert-success text-center shadow-sm rounded-3">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center shadow-sm rounded-3">
                {{ session('error') }}
            </div>
        @endif

{{-- Résultats --}}
@if(!empty($searchActive))
    @if($covoiturages->isNotEmpty())
        <div class="container mt-4">
            <div class="row justify-content-center">
                @foreach($covoiturages as $covoiturage)
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ optional($covoiturage->voiture->utilisateur)->photo ?? 'https://via.placeholder.com/50' }}"
                                         alt="Photo chauffeur" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold">{{ optional($covoiturage->voiture->utilisateur)->pseudo ?? '...' }}</div>
                                        <div class="text-muted small">⭐ {{ $covoiturage->avis->avg('note') ? number_format($covoiturage->avis->avg('note'),1) : '0' }}/5</div>
                                    </div>
                                </div>

                                <div class="text-center ms-md-auto me-md-3">
                                    <div class="small text-muted">{{ $covoiturage->nb_place }} places restantes</div>
                                    <div class="fw-bold text-green fs-5">{{ $covoiturage->prix_personne }} €</div>
                                </div>

                                <div class="flex-grow-1 text-start">
                                    <div><strong>Départ&nbsp;:</strong> {{ $covoiturage->lieu_depart }} - {{ $covoiturage->date_depart->format('d/m/Y') }} {{ $covoiturage->heure_depart }}</div>
                                    <div><strong>Arrivée&nbsp;:</strong> {{ $covoiturage->lieu_arrivee }} - {{ \Carbon\Carbon::parse($covoiturage->date_arrivee)->format('d/m/Y') }}</div>
                                    <div class="small mt-1 {{ $covoiturage->voiture->ecologique ? 'text-green' : 'text-muted' }}">
                                    {{ $covoiturage->voiture->ecologique ? 'Voyage écologique 🌱' : 'Classique 🚗' }}</div>
                                </div>

                                <!-- Bouton Détails -->
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCovoiturage{{ $covoiturage->id }}">
                                    Détails
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalCovoiturage{{ $covoiturage->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $covoiturage->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title" id="modalLabel{{ $covoiturage->id }}">
                                                    🚗 Trajet {{ $covoiturage->lieu_depart }} → {{ $covoiturage->lieu_arrivee }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Départ :</strong> {{ $covoiturage->lieu_depart }} - {{ $covoiturage->date_depart->format('d/m/Y') }} {{ $covoiturage->heure_depart }}</p>
                                                <p><strong>Arrivée :</strong> {{ $covoiturage->lieu_arrivee }} - {{ \Carbon\Carbon::parse($covoiturage->date_arrivee)->format('d/m/Y') }}</p>
                                                <p><strong>Prix :</strong> {{ $covoiturage->prix_personne }} € / personne</p>
                                                <p><strong>Places restantes :</strong> {{ $covoiturage->nb_place }}</p>
                                                <p><strong>Conducteur :</strong> {{ optional($covoiturage->voiture->utilisateur)->pseudo ?? 'N/A' }}</p>
                                                <p><strong>Note moyenne :</strong> ⭐ {{ $covoiturage->avis->avg('note') ? number_format($covoiturage->avis->avg('note'),1) : '0' }}/5</p>
                                                @if($covoiturage->avis->count() > 0) <p><strong>Avis des passagers :</strong></p>
                                                    <ul>
                                                        @foreach($covoiturage->avis as $avis)
                                                            <li>"{{ $avis->commentaire }}" — ⭐ {{ $avis->note }}/5</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p><em>Aucun avis pour ce conducteur.</em></p>
                                                @endif
                                                <p><strong>Type :</strong> {{ $covoiturage->voiture->ecologique ? 'Voyage écologique 🌱' : 'Classique 🚗' }}</p>
                                                <p><strong>Véhicule :</strong> {{ $covoiturage->voiture->marque ?? 'N/A' }} {{ $covoiturage->voiture->modele ?? '' }} ({{ $covoiturage->voiture->energie ?? 'inconnu' }})</p>
                                                @if(!empty($covoiturage->voiture->preferences))
                                                    <div class="mt-2 p-2 bg-light border rounded">
                                                            <h6 class="fw-bold mb-1"><i class="bi bi-stars"></i> Préférences conducteur :</h6>
                                                            <ul class="list-unstyled mb-0">
                                                                <li>
                                                                    <i class="bi {{ ($covoiturage->voiture->preferences['fumeur'] ?? false) ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                                                    <strong>Fumeur :</strong> {{ ($covoiturage->voiture->preferences['fumeur'] ?? false) ? 'Oui' : 'Non' }}
                                                                </li>
                                                                <li>
                                                                    <i class="bi {{ ($covoiturage->voiture->preferences['animal'] ?? false) ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                                                    <strong>Animaux :</strong> {{ ($covoiturage->voiture->preferences['animal'] ?? false) ? 'Acceptés' : 'Non acceptés' }}
                                                                </li>
                                                                @if(!empty($covoiturage->voiture->preferences['custom']))
                                                                    <li class="mt-1">
                                                                        <strong>Autres préférences :</strong>
                                                                        <ul class="mb-0">
                                                                            @foreach($covoiturage->voiture->preferences['custom'] as $pref)
                                                                                <li>{{ $pref }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <p><strong>Préférences conducteur :</strong> Non renseignées</p>
                                                    @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                @if(Auth::check())
                                                    @if($covoiturage->nb_place > 0)
                                                        <form id="reservation-form-{{ $covoiturage->id }}"
                                                            data-prix="{{ $covoiturage->prix_personne }}"
                                                            action="{{ route('covoiturage.reserver', $covoiturage->id) }}"
                                                            method="POST"
                                                            class="m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success">
                                                                Participer à ce covoiturage
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-secondary" disabled>Aucune place disponible</button>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-outline-dark">Se connecter pour réserver</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($alternatives->isNotEmpty())
        <div class="container-md mt-4">
                @if($raison === 'filtres')
                    <p class="text-center fw-bold text-danger">Aucun covoiturage avec vos filtres. Voici quelques propositions proches :</p>
                @else
                    <p class="text-center fw-bold text-danger">Aucun covoiturage exact disponible. Voici les itinéraires les plus proches :</p>
                @endif

            <div class="row justify-content-center">
                @foreach($alternatives as $covoiturage)
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ optional($covoiturage->voiture->utilisateur)->photo ?? 'https://via.placeholder.com/50' }}"
                                         alt="Photo chauffeur" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold">{{ optional($covoiturage->voiture->utilisateur)->pseudo ?? '...' }}</div>
                                        <div class="text-muted small">⭐ {{ $covoiturage->avis->avg('note') ? number_format($covoiturage->avis->avg('note'),1) : '0' }}/5</div>
                                    </div>
                                </div>

                                <div class="text-center ms-md-auto me-md-3">
                                    <div class="small text-muted">{{ $covoiturage->nb_place }} places restantes</div>
                                    <div class="fw-bold text-green fs-5">{{ $covoiturage->prix_personne }} €</div>
                                </div>

                                <div class="flex-grow-1 text-start">
                                    <div><strong>Départ&nbsp;:</strong> {{ $covoiturage->lieu_depart }} - {{ $covoiturage->date_depart->format('d/m/Y') }} {{ $covoiturage->heure_depart }}</div>
                                    <div><strong>Arrivée&nbsp;:</strong> {{ $covoiturage->lieu_arrivee }} - {{ \Carbon\Carbon::parse($covoiturage->date_arrivee)->format('d/m/Y') }}</div>
                                    <div class="small mt-1 {{ $covoiturage->voiture->ecologique ? 'text-green' : 'text-muted' }}">
                                    {{ $covoiturage->voiture->ecologique ? 'Voyage écologique 🌱' : 'Classique 🚗' }}</div>
                                </div>

                                <!-- Bouton Détails -->
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCovoiturage{{ $covoiturage->id }}">
                                    Détails
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalCovoiturage{{ $covoiturage->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $covoiturage->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content shadow-lg">
                                            <div class="modal-header bg-dark text-white">
                                                <h5 class="modal-title" id="modalLabel{{ $covoiturage->id }}">
                                                    🚗 Trajet {{ $covoiturage->lieu_depart }} → {{ $covoiturage->lieu_arrivee }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Départ :</strong> {{ $covoiturage->lieu_depart }} - {{ $covoiturage->date_depart->format('d/m/Y') }} {{ $covoiturage->heure_depart }}</p>
                                                <p><strong>Arrivée :</strong> {{ $covoiturage->lieu_arrivee }} - {{ \Carbon\Carbon::parse($covoiturage->date_arrivee)->format('d/m/Y') }}</p>
                                                <p><strong>Prix :</strong> {{ $covoiturage->prix_personne }} € / personne</p>
                                                <p><strong>Places restantes :</strong> {{ $covoiturage->nb_place }}</p>
                                                <p><strong>Conducteur :</strong> {{ optional($covoiturage->voiture->utilisateur)->pseudo ?? 'N/A' }}</p>
                                                <p><strong>Note moyenne :</strong> ⭐ {{ $covoiturage->avis->avg('note') ? number_format($covoiturage->avis->avg('note'),1) : '0' }}/5</p>
                                                @if($covoiturage->avis->count() > 0) <p><strong>Avis des passagers :</strong></p>
                                                    <ul>
                                                        @foreach($covoiturage->avis as $avis)
                                                            <li>"{{ $avis->commentaire }}" — ⭐ {{ $avis->note }}/5</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p><em>Aucun avis pour ce conducteur.</em></p>
                                                @endif
                                                <p><strong>Type :</strong> {{ $covoiturage->voiture->ecologique ? 'Voyage écologique 🌱' : 'Classique 🚗' }}</p>
                                                <p><strong>Véhicule :</strong> {{ $covoiturage->voiture->marque ?? 'N/A' }} {{ $covoiturage->voiture->modele ?? '' }} ({{ $covoiturage->voiture->energie ?? 'inconnu' }})</p>
                                                @if(!empty($covoiturage->voiture->preferences))
                                                    <div class="mt-2 p-2 bg-light border rounded">
                                                            <h6 class="fw-bold mb-1"><i class="bi bi-stars"></i> Préférences conducteur :</h6>
                                                            <ul class="list-unstyled mb-0">
                                                                <li>
                                                                    <i class="bi {{ ($covoiturage->voiture->preferences['fumeur'] ?? false) ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                                                    <strong>Fumeur :</strong> {{ ($covoiturage->voiture->preferences['fumeur'] ?? false) ? 'Oui' : 'Non' }}
                                                                </li>
                                                                <li>
                                                                    <i class="bi {{ ($covoiturage->voiture->preferences['animal'] ?? false) ? 'bi-check-circle text-success' : 'bi-x-circle text-danger' }}"></i>
                                                                    <strong>Animaux :</strong> {{ ($covoiturage->voiture->preferences['animal'] ?? false) ? 'Acceptés' : 'Non acceptés' }}
                                                                </li>
                                                                @if(!empty($covoiturage->voiture->preferences['custom']))
                                                                    <li class="mt-1">
                                                                        <strong>Autres préférences :</strong>
                                                                        <ul class="mb-0">
                                                                            @foreach($covoiturage->voiture->preferences['custom'] as $pref)
                                                                                <li>{{ $pref }}</li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @else
                                                        <p><strong>Préférences conducteur :</strong> Non renseignées</p>
                                                    @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    @if(Auth::check())
                                                        @if($covoiturage->nb_place > 0)
                                                            <form id="reservation-form-{{ $covoiturage->id }}"
                                                                data-prix="{{ $covoiturage->prix_personne }}"
                                                                action="{{ route('covoiturage.reserver', $covoiturage->id) }}"
                                                                method="POST"
                                                                class="m-0">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success">
                                                                    Participer à ce covoiturage
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-secondary" disabled>Aucune place disponible</button>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('login') }}" class="btn btn-outline-dark">Se connecter pour réserver</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>

        @else
            <p class="text-center fw-bold mt-4 text-danger">Aucun covoiturage trouvé. Essayez de modifier vos critères.</p>
        @endif
    @endif

</div>
</section>
</main>
@endsection