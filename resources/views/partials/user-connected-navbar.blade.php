<div class="collapse navbar-collapse" id="navbarNav">
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
                        {{ auth()->user()->nom ?? auth()->user()->email }}
                    </div>
                    <small class="text-warning">
                        {{ number_format(
                            (auth()->user()->solde_reel ?? 0) +
                            (auth()->user()->solde_bonus ?? 0),
                            0, ',', ' '
                        ) }}
                        {{ auth()->user()->pays->devise->symbol }}
                    </small>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 260px;">

                {{-- HEADER WALLET --}}
                <li class="px-3 py-3 bg-light border-bottom">

                    <div class="small mt-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Solde réel</span>
                            <span class="fw-semibold">
                                {{ number_format(auth()->user()->solde_reel ?? 0, 0, ',', ' ') }}
                                {{ auth()->user()->pays->devise->symbol }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Bonus</span>
                            <span class="text-warning fw-semibold">
                                {{ number_format(auth()->user()->solde_bonus ?? 0, 0, ',', ' ') }}
                                {{ auth()->user()->pays->devise->symbol }}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Solde disponible</span>

                            <div class="fw-bold text-success fw-semibold">
                                {{ number_format(
                                    (auth()->user()->solde_reel ?? 0) +
                                    (auth()->user()->solde_bonus ?? 0),
                                    0, ',', ' '
                                ) }}
                                {{ auth()->user()->pays->devise->symbol }}
                            </div>
                        </div>
                    </div>

                    <a class="btn btn-success btn-sm w-100 mt-3"
                       href="{{ route('user.transactions.deposit', auth()->user()) }}">
                        <i class="bi bi-plus-circle me-1"></i>
                        Ajouter des fonds
                    </a>
                </li>

                {{-- MENU --}}
                <li>
                    <a class="dropdown-item py-2" href="{{-- route('profile.show') --}}">
                        <i class="bi bi-person me-2 text-muted"></i>
                        Mon profil
                    </a>
                </li>

                <li>
                    <a class="dropdown-item py-2" href="{{-- route('settings.index') --}}">
                        <i class="bi bi-gear me-2 text-muted"></i>
                        Paramètres
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger py-2">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </li>

            </ul>
        </li>

    </ul>
</div>
