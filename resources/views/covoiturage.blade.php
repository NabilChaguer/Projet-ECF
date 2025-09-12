@extends('layouts.app')

@section('content')

<main class="flex-grow-1 d-flex justify-content-center bg-gradient-section h-100">
<section class="container">
    
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

{{-- Résultats --}}
@if(request()->isMethod('post'))
    @if($covoiturages->isNotEmpty())
        <div class="container mt-4">
            <div class="row justify-content-center">
                @foreach($covoiturages as $covoiturage)
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">

                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $covoiturage->voiture->utilisateur->photo ?? 'https://via.placeholder.com/50' }}"
                                         alt="Photo chauffeur" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold">{{ $covoiturage->voiture->utilisateur->pseudo }}</div>
                                        <div class="text-muted small">⭐ {{ $covoiturage->avis->avg('note') ?? 0 }}/5</div>
                                    </div>
                                </div>

                                <div class="text-center ms-md-auto me-md-3">
                                    <div class="small text-muted">{{ $covoiturage->nb_place }} places restantes</div>
                                    <div class="fw-bold text-green fs-5">{{ $covoiturage->prix_personne }} €</div>
                                </div>

                                <div class="flex-grow-1 text-start">
                                    <div><strong>Départ&nbsp;:</strong> {{ $covoiturage->lieu_depart }} - {{ \Carbon\Carbon::parse($covoiturage->date_depart)->format('d/m/Y H:i') }}</div>
                                    <div><strong>Arrivée&nbsp;:</strong> {{ $covoiturage->lieu_arrivee }} - {{ \Carbon\Carbon::parse($covoiturage->date_arrivee)->format('d/m/Y H:i') }}</div>
                                    <div class="small mt-1 {{ $covoiturage->ecologique ? 'text-green' : 'text-muted' }}">
                                        {{ $covoiturage->ecologique ? 'Voyage écologique 🌱' : 'Classique 🚗' }}
                                    </div>
                                </div>

                                <div>
                                    <a href="#" class="btn btn-dark">Détails</a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @elseif($alternatives->isNotEmpty())
        <div class="container-md mt-4">
            <p class="text-center fw-bold">
                Aucun covoiturage exact disponible.<br>
                Voici les itinéraires les plus proches :
            </p>
            <div class="row justify-content-center">
                @foreach($alternatives as $covoiturage)
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">

                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $covoiturage->voiture->utilisateur->photo ?? 'https://via.placeholder.com/50' }}"
                                         alt="Photo chauffeur" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                                    <div>
                                        <div class="fw-semibold">{{ $covoiturage->voiture->utilisateur->pseudo }}</div>
                                        <div class="text-muted small">⭐ {{ $covoiturage->avis->avg('note') ?? 0 }}/5</div>
                                    </div>
                                </div>

                                <div class="text-center ms-md-auto me-md-3">
                                    <div class="small text-muted">{{ $covoiturage->nb_place }} places restantes</div>
                                    <div class="fw-bold text-green fs-5">{{ $covoiturage->prix_personne }} €</div>
                                </div>

                                <div class="flex-grow-1 text-start">
                                    <div><strong>Départ&nbsp;:</strong> {{ $covoiturage->lieu_depart }} - {{ \Carbon\Carbon::parse($covoiturage->date_depart)->format('d/m/Y H:i') }}</div>
                                    <div><strong>Arrivée&nbsp;:</strong> {{ $covoiturage->lieu_arrivee }} - {{ \Carbon\Carbon::parse($covoiturage->date_arrivee)->format('d/m/Y H:i') }}</div>
                                    <div class="small mt-1 {{ $covoiturage->ecologique ? 'text-green' : 'text-muted' }}">
                                        {{ $covoiturage->ecologique ? 'Voyage écologique 🌱' : 'Classique 🚗' }}
                                    </div>
                                </div>

                                <div>
                                    <a href="#" class="btn btn-dark">Détails</a>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <p class="text-center fw-bold mt-4">
                    Aucun covoiturage proche n’a été trouvé.<br>
                    Essayez de modifier vos critères de recherche.
                </p>
        </div>
    @endif
@endif
  </div>
</section>
</main>
@endsection