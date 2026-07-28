<ul class="navbar-nav flex-grow-1 d-flex justify-content-center align-items-center">
    <li class="nav-item px-2">
        <a class="nav-link text-white d-inline-flex align-items-center {{ request()->is('/') ? 'active-link' : '' }}" href="{{ url('/') }}">
            <i class="bi bi-house me-2"></i>
            <span>Accueil</span>
        </a>
    </li>    

    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('login') ? 'active-link' : '' }}" href="{{ route('login') }}">
                <span>Se connecter</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('register') ? 'active-link' : '' }}" href="{{ route('register') }}">
                <span>S'inscrire</span>
            </a>
        </li>
    </ul>
</ul>