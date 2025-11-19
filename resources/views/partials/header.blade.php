<nav id="navbar" class="navbar navbar-expand-lg fixed-top py-3">
    <div class="container">

        <a class="navbar-brand fw-bold text-success" href="/">
            <i class="bi bi-flower1"></i> EcoRide
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
            <div class="offcanvas-header">
                <h5 class="fw-bold text-success">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="{{ route('mon-espace') }}">
                                <i class="bi bi-person-circle"></i> Mon espace
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="/">Accueil</a>
                        </li>
                    @endauth

                    <li class="nav-item"><a class="nav-link text-dark" href="/covoiturage">Covoiturage</a></li>
                    <li class="nav-item"><a class="nav-link text-dark" href="/contact">Contact</a></li>

                    @guest
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('login.formulaire') }}" class="btn btn-color px-3">
                                <i class="bi bi-box-arrow-in-right"></i> Connexion
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item ms-lg-3">
                            <span class="text-dark me-3">
                                Bonjour, <strong>{{ Auth::user()->pseudo }}</strong><br>
                                <small>Crédits restants : <strong>{{ Auth::user()->credit }}</strong></small>
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-dark px-3">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>
