@extends('layouts.app')

@section('title', 'EcoRide - Accueil')



    <!-- First section -->
    <section class="py-5 bg-gradient-section">
        <div class="container text-center text-md-start mt-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold">
                        Voyage Durable pour un <span class="text-green">Avenir Plus Vert</span>
                    </h1>
                    <p>
                        Rejoignez notre communauté de covoiturage éco-responsable et réduisez votre empreinte carbone
                        tout en économisant de l'argent et en faisant de nouvelles rencontres.
                    </p>
                    <form action="{{ url('/chercher-routes') }}" method="GET" class="p-4 bg-white rounded-4 shadow-sm">
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </span>
                                    <input type="text" name="from" class="form-control border-start-0" placeholder="From" value="{{ old('depart') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </span>
                                    <input type="text" name="to" class="form-control border-start-0" placeholder="To" value="{{ old('arrivee') }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-color w-100">Rechercher</button>
                    </form>
                </div>
                <img src="{{ asset('images/img-voiture.svg') }}" alt="Voiture Verte" class="img-fluid col-md-6 text-center p-4">
            </div>
        </div>
    </section>

    <!-- Second section -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold mb-4">Pourquoi Choisir EcoRide ?</h2>
            <p class="mb-5">Notre plateforme connecte les voyageurs soucieux de l’environnement pour des trajets durables</p>
            <div class="row g-4">
                <div class="col-md-4 d-flex">
                    <div class="p-4 shadow-lg bloc-color flex-fill">
                        <h5>🌱 Éco-Responsable</h5>
                        <p>Réduisez les émissions de CO2 en partageant vos trajets et contribuez à un environnement plus propre.</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex">
                    <div class="p-4 shadow-lg bloc-color flex-fill">
                        <h5>💰 Économique</h5>
                        <p>Économisez sur le carburant et les péages en partageant les frais de voyage avec d'autres passagers.</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex">
                    <div class="p-4 shadow-lg bloc-color flex-fill">
                        <h5>🤝 Communauté</h5>
                        <p>Connectez-vous avec des voyageurs partageant les mêmes valeurs et créez des liens durables.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Third section -->
    <section class="py-5 bg-success text-white">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="{{ asset('images/img-foret.svg') }}" alt="Nature" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <h3 class="fw-bold mb-3">Notre Impact Environnemental</h3>
                    <p class="mb-4 text-muted">
                        Ensemble, nous faisons une réelle différence en réduisant nos émissions de carbone
                        et en promouvant des transports plus durables et respectueux de l’environnement.
                    </p>
                    <div class="d-flex gap-5 justify-content-center">
                        <div>
                            <h4 class="fw-bold">{{ $co2_saved ?? '2,5M+' }}</h4>
                            <small class="fs-6 text-muted">CO2 Économisé (kg)</small>
                        </div>
                        <div>
                            <h4 class="fw-bold">{{ $happy_users ?? '50K+' }}</h4>
                            <small class="fs-6 text-muted">Utilisateurs Satisfaits</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fourth section -->
    <section class="py-5 text-center">
        <div class="container">
            <h3 class="fw-bold">Prêt à Commencer Votre Voyage Éco ?</h3>
            <p>Rejoignez des milliers de voyageurs soucieux de l’environnement dès aujourd’hui</p>
            <a href="/commencer" class="btn btn-color me-2">Commencer</a>
        </div>
    </section>

