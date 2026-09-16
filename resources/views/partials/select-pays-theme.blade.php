{{-- Pays actif (session ou défaut) --}}
        @php
            $currentPays = session('pays') ?? $paysList->first();
        @endphp

        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
        href="#" role="button" data-bs-toggle="dropdown">

            <img src="https://flagcdn.com/w20/{{ strtolower($currentPays->code) }}.png"
                class="rounded" alt="{{ $currentPays->nom }}">

            <span>{{ $currentPays->nom }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end shadow-sm">

            @foreach($paysList as $pays)
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2"
                    href="{{ route('change.pays', $pays->id) }}">

                        <img src="https://flagcdn.com/w20/{{ strtolower($pays->code) }}.png"
                            alt="{{ $pays->nom }}">

                        <div style="color: #120657">
                            <div class="fw-semibold">{{ $pays->nom }}</div>
                            <small class="text-muted">{{ $pays->langue_officielle }}</small>
                        </div>
                    </a>
                </li>
            @endforeach

        </ul>
    </li>
</ul>

{{-- Dark mode toggle --}}
<div class="text-end mb-2">
    <button class="btn btn-sm btn-outline-warning"
            id="themeToggle"
            onclick="toggleTheme()"
            title="Changer de mode">
        <i id="themeIcon" class="bi bi-sun-fill"></i>
    </button>
</div>