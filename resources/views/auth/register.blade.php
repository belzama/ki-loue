@extends('layouts.app')

@section('nav-bar')
    @include('partials.welcome-navbar')
@endsection

@section('main-content')

<div class="section bg-img container-fluid min-vh-100 d-flex align-items-center justify-content-center py-3">

    <div class="shadow-lg border-0 rounded-4" style="background:white; margin-top:30px; max-width:520px; width:100%;">
        <div class="card-body p-3">

            {{-- HEADER --}}
            <div class="text-center mb-1">
                <img src="{{ asset('images/logo_fond_blanc.png') }}" alt="Rentalpark" class="d-block mx-auto mb-1" height="120">
                <h6 class="fw-bold mb-0">Inscription</h6>
                <small class="text-muted">Créez votre compte</small>
            </div>

            @if($errors->any())
                <div class="alert alert-danger small py-1 mb-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- NOM + PRÉNOM --}}
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" value="{{ old('nom') }}"
                               class="form-control form-control-sm" required>
                        @error('nom')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Prénom(s) <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                               class="form-control form-control-sm" required>
                        @error('prenom')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>

                {{-- TYPE UTILISATEUR --}}
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="type" id="type1" value="Société" checked>
                    <label class="btn btn-outline-primary" for="type1">Société</label>

                    <input type="radio" class="btn-check" name="type" id="type2" value="Particulier">
                    <label class="btn btn-outline-primary" for="type2">Particulier</label>
                </div>

                {{-- RAISON SOCIALE --}}
                <div class="mb-3" id="raison_sociale_block">
                    <label class="form-label">
                        Raison sociale <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                        name="raison_sociale"
                        value="{{ old('raison_sociale') }}"
                        class="form-control">

                    @error('raison_sociale')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- PAYS + PSEUDO --}}
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Pays <span class="text-danger">*</span></label>
                        <select id="pays_id" name="pays_id" class="form-select form-select-sm" required>
                            <option value="">Sélectionner</option>
                            @foreach($pays as $p)
                                <option value="{{ $p->id }}" data-code="{{ $p->code_iso }}"
                                        {{ old('pays_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Pseudo</label>
                        <input type="text" name="code" value="{{ old('code') }}"
                               class="form-control form-control-sm"
                               placeholder="Généré automatiquement">
                    </div>
                </div>

                {{-- TÉLÉPHONE + WHATSAPP --}}
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Téléphone <span class="text-danger">*</span></label>
                        <input type="tel" id="telephone" name="telephone"
                               value="{{ old('telephone') }}"
                               class="form-control form-control-sm" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">WhatsApp</label>
                        <input type="tel" id="whatsapp" name="whatsapp"
                               value="{{ old('whatsapp') }}"
                               class="form-control form-control-sm">
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="mb-2">
                    <label class="form-label form-label-sm mb-0">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control form-control-sm" required>
                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                {{-- MOT DE PASSE + CONFIRMATION --}}
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                               class="form-control form-control-sm" required>
                        @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label form-label-sm mb-0">Confirmer <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation"
                               class="form-control form-control-sm" required>
                    </div>
                </div>

                {{-- CODE SPONSOR --}}
                <div class="mb-3">
                    <label class="form-label form-label-sm mb-0">Code sponsor <span class="text-muted">(optionnel)</span></label>
                    <input type="text" name="ref_code"
                           value="{{ old('ref_code', request('ref')) }}"
                           class="form-control form-control-sm">
                </div>

                {{-- SUBMIT --}}
                <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> S'inscrire
                </button>

                <div class="text-center mt-2">
                    <a class="small text-decoration-none" href="{{ route('login') }}">
                        Déjà inscrit ? Se connecter
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Raison sociale ──────────────────────────────────────────
function toggleRaisonSociale() {
    const type  = document.querySelector('input[name="type"]:checked').value;
    const block = document.getElementById("raison_sociale_block");
    block.style.display = type === "Société" ? "block" : "none";
}

// ── Init au chargement ──────────────────────────────────────
document.addEventListener("DOMContentLoaded", function () {

    toggleRaisonSociale();

    document.querySelectorAll('input[name="type"]').forEach(el => {
        el.addEventListener("change", toggleRaisonSociale);
    });

    // ── intlTelInput ────────────────────────────────────────
    const telInput      = document.querySelector("#telephone");
    const whatsappInput = document.querySelector("#whatsapp");

    const itiTel = window.intlTelInput(telInput, {
        initialCountry: "auto",
        nationalMode: false,
        preferredCountries: ["tg", "ci", "sn", "fr"],
        geoIpLookup: function (callback) {
            fetch("https://ipapi.co/json")
                .then(res => res.json())
                .then(data => callback(data.country_code.toLowerCase()))
                .catch(() => callback("tg"));
        },
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
    });

    const itiWhatsapp = window.intlTelInput(whatsappInput, {
        initialCountry: "auto",
        nationalMode: false,
        preferredCountries: ["tg", "ci", "sn", "fr"],
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
    });

    // ── Injecter les numéros complets avant soumission ──────
    document.querySelector('form').addEventListener('submit', function () {
        telInput.value      = itiTel.getNumber();
        whatsappInput.value = itiWhatsapp.getNumber();
    });
});
</script>
@endpush
