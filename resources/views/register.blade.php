@extends('layouts.app')

@section('title', 'EcoRide - Inscription')

@section('content')

<main class="d-flex justify-content-center align-items-center bg-gradient-section h-100">
    <div class="card shadow-lg p-3">
        <h3 class="text-center text-success mb-4">Créer un compte EcoRide</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="pseudo" class="form-label">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" class="form-control" placeholder="Votre pseudo" value="{{ old('pseudo') }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="exemple@ecoride.fr" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" class="form-control" placeholder="••••••••" required>
                <div class="form-text" style="font-size: 0.8rem;">
                    8 caractères minimum, dont une majuscule, un chiffre et un symbole.
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-color">Créer un compte</button>
            </div>
        </form>

        <hr class="my-3">
        <p class="text-center mb-0">
            Déjà un compte ?
            <a href="{{ route('login.formulaire') }}" class="text-success fw-bold">Se connecter</a>
        </p>
    </div>
</main>
@endsection