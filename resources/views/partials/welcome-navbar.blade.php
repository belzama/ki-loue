<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto">
        @guest
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Se connecter</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">S'inscrire</a></li>
        @endguest

        @auth
            @if(auth()->user()->role === 'Admin')
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a></li>
            @endif
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-link nav-link" type="submit">Déconnexion</button>
                </form>
            </li>
        @endauth
    </ul>
</div>