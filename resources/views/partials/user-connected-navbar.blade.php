<ul class="navbar-nav flex-grow-1 d-flex justify-content-center align-items-center">

    <li class="nav-item">
        <a class="nav-link text-white d-inline-flex align-items-center {{ request()->is('/') ? 'active' : '' }}"
           href="{{ url('/') }}">
            <i class="bi bi-house me-2"></i>
            <span>Accueil</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white d-inline-flex align-items-center {{ request()->routeIs('user.dispositifs.*') ? 'active' : '' }}"
           href="{{ route('user.dispositifs.index') }}">
            <i class="bi bi-truck me-2"></i>
            <span>Matériels</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white d-inline-flex align-items-center {{ request()->routeIs('user.publications.*') ? 'active' : '' }}"
           href="{{ route('user.publications.index') }}">
            <i class="bi bi-journal-text me-2"></i>
            <span>Publications</span>
        </a>
    </li>

    @if(auth()->user()->role === 'Admin')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-person me-2"></i>
                <span>Mon compte</span>
            </a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
               href="{{ route('user.dashboard') }}">
                <i class="bi bi-person me-2"></i>
                <span>Mon compte</span>
            </a>
        </li>
    @endif

    @php
        $count = auth()
        ->user()->notifications()
        ->where('read', false)
        ->count();
    @endphp

    @if($count > 0)
        <li class="nav-item px-2 position-relative">
            <a class="nav-link text-white d-inline-flex align-items-center" href="{{-- route('notifications.index') --}}">
                <i class="bi bi-bell me-2"></i>
                    <span class="badge rounded-pill bg-danger">
                    {{ $count }}
                </span>
            </a>
        </li>
    @endif
</ul>

<ul class="navbar-nav ms-auto align-items-center">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white d-flex align-items-center"
            href="#"
            id="userDropdown"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">

            <i class="bi bi-person-circle fs-4 me-2"></i>

            <div class="lh-sm">
                <div class="fw-semibold">
                    {{ auth()->user()->nom ?? '' }}
                    {{ auth()->user()->prenom ?? '' }}
                </div>

<!--                 <div class="fw-bold">
                    <small class="text-white">
                        {{ auth()->user()->raison_sociale ?? auth()->user()->email }}
                    </small>
                </div>

                <small class="text-warning">
                    {{ number_format(
                        (auth()->user()->solde_reel ?? 0) +
                        (auth()->user()->solde_bonus ?? 0),
                        0, ',', ' '
                    ) }}
                    {{ auth()->user()->pays->devise->symbol }}
                </small> -->
            </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0 overflow-hidden" style="min-width: 300px; border-radius: 20px;">

            {{-- HEADER WALLET --}}
            <li class="header-wallet px-3 py-3" style="background: var(--rp-navy)">
                <div style="border-radius: 20px; overflow: hidden;">
                    <div class="d-flex align-items-stretch text-center">
                        <div class="flex-fill px-2 bg-white border">
                            <div class="text-muted small text-uppercase fw-semibold">Solde réel</div>
                            <div class="fw-bold fs-5 text-navy">
                                {{ number_format(auth()->user()->solde_reel ?? 0, 0, ',', ' ') }}
                                {{ auth()->user()->pays->devise->symbol }}
                            </div>
                        </div>

                        <div class="flex-fill px-2 bg-white border">
                            <div class="text-muted small text-uppercase fw-semibold">Bonus</div>
                            <div class="fw-bold fs-5 text-navy">
                                {{ number_format(auth()->user()->solde_bonus ?? 0, 0, ',', ' ') }}
                                {{ auth()->user()->pays->devise->symbol }}
                            </div>
                        </div>
                    </div>
                    {{-- SOLDE DISPONIBLE --}}
                    <div class="text-center py-3 px-3 border" style="background: var(--rp-orange);">
                        <div class="text-white fw-semibold">Solde disponible</div>
                        <div class="text-white fw-bold fs-4">
                            {{ number_format(
                                (auth()->user()->solde_reel ?? 0) +
                                (auth()->user()->solde_bonus ?? 0),
                                0, ',', ' '
                            ) }}
                            {{ auth()->user()->pays->devise->symbol }}
                        </div>
                    </div>
                </div>

                {{-- AJOUTER DES FONDS --}}
                <div class="py-3">
                    <a class="btn w-100 fw-semibold py-2"
                    style="background: var(--rp-orange); color: #1a1a2e; border-radius: 10px;"
                    href="{{ route('user.transactions.deposit', auth()->user()) }}">
                        <i class="bi bi-plus-circle me-1"></i>
                        Ajouter des fonds
                    </a>
                </div>
            </li>

            {{-- MON PROFIL --}}
            <li style="background: var(--rp-navy);">
                <a class="dropdown-item d-flex align-items-center py-3 px-3 text-white fw-semibold"
                href="{{ route('user.profile.show') }}">
                    <i class="bi bi-person-circle me-2 fs-5"></i>
                    Mon profil
                </a>
            </li>

            {{-- DECONNEXION --}}
            <li style="background: var(--rp-navy);">
                <form action="{{ route('logout') }}" method="POST" class="m-0"
                data-waiting
                data-waiting-message="Déconnexion en cours...">
                    @csrf
                    <button class="dropdown-item d-flex align-items-center py-3 px-3 fw-semibold border-0 bg-transparent"
                            style="color: var(--rp-orange);">
                        <i class="bi bi-box-arrow-right me-2 fs-5"></i>
                        Deconnexion
                    </button>
                </form>
            </li>

        </ul>
    </li>
</ul>
