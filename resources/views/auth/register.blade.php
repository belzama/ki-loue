@extends('layouts.app')

@section('nav-bar')
{{-- Navbar masquée --}}
@endsection

@section('main-content')

<div class="container-fluid vh-100">
    <div class="row h-100">

        {{-- IMAGE GAUCHE --}}
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center bg-login-image">
            <div class="text-white text-center px-5">
                <h1 class="fw-bold mb-3">Plateforme BTP</h1>
                <p class="lead">
                    Gérez vos équipements, locations et utilisateurs en toute sécurité
                </p>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        <div class="col-md-6 d-flex align-items-center justify-content-center bg-body">

            <div class="card shadow-lg border-0 rounded-4" style="max-width:450px; width:100%;">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <div class="fs-1">🔐</div>
                        <h4 class="fw-bold">Inscription</h4>
                        <small class="text-muted">Créez votre compte</small>
                    </div>

                    {{-- ERREURS --}}
                    @if($errors->any())
                        <div class="alert alert-danger small">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- NOM --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="nom"
                                   value="{{ old('nom') }}"
                                   class="form-control"
                                   required>
                            @error('nom')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- PRENOM --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Prénom(s) <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="prenom"
                                   value="{{ old('prenom') }}"
                                   class="form-control"
                                   required>
                            @error('prenom')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
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
                        
                        {{-- PAYS --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Pays <span class="text-danger">*</span>
                            </label>

                            <select id="pays_id" name="pays_id" class="form-select" required>
                                <option value="">Sélectionner</option>
                                @foreach($pays as $p)
                                    <option value="{{ $p->id }}"
                                            data-code="{{ $p->code_iso }}"
                                            {{ old('pays_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- CONTACT --}}
                        <div class="row g-3 mb-3">

                            {{-- TELEPHONE --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    Téléphone <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <input type="tel"
                                        id="telephone"
                                        name="telephone"
                                        value="{{ old('telephone') }}"
                                        class="form-control"
                                        required>
                                </div>
                            </div>                            

                            {{-- WHATSAPP --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    WhatsApp <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">
                                    <input type="tel"
                                        id="whatsapp"
                                        name="whatsapp"
                                        value="{{ old('whatsapp') }}"
                                        class="form-control">
                                </div>
                            </div>

                        </div>
                        
                        {{-- PSEUDO --}}
                        <div class="mb-3">
                            <label class="form-label">Pseudo</label>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code') }}"
                                   class="form-control">
                            <small class="text-muted">Laissez vide pour générer automatiquement</small>
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control"
                                   required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- CONFIRMATION --}}
                        <div class="mb-4">
                            <label class="form-label">
                                Confirmer mot de passe <span class="text-danger">*</span>
                            </label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required>
                            @error('password_confirm')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- CODE SPONSOR --}}
                        <div class="mb-3">
                            <label class="form-label">Code sponsor (optionnel)</label>
                            <input type="text"
                                   name="ref_code"
                                   value="{{ old('ref_code', request('ref')) }}"
                                   class="form-control">
                        </div>

                        {{-- ACTIONS --}}
                        <button type="submit"
                                class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            S'inscrire
                        </button>

                        <div class="text-center mt-3">
                            <a class="small text-decoration-none"
                               href="{{ route('login') }}">
                                Vous êtes déjà inscrit ?
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>

let countryCodes = {};

async function loadCountryCodes() {

    try {

        const res = await fetch('https://restcountries.com/v3.1/all?fields=name,idd,flags');

        if (!res.ok) {
            throw new Error("Erreur API : " + res.status);
        }

        const countries = await res.json();

        if (!Array.isArray(countries)) {
            throw new Error("Format API incorrect");
        }

        countries.forEach(country => {

            if (country.idd && country.idd.root) {

                const code = country.idd.root + (country.idd.suffixes ? country.idd.suffixes[0] : '');

                console.log(country.name.common + " " + code);

            }

        });

    } catch (error) {

        console.error("Erreur chargement pays :", error);

    }

}

loadCountryCodes();

function toggleRaisonSociale(){

    const type = document.querySelector('input[name="type"]:checked').value;
    const block = document.getElementById("raison_sociale_block");

    if(type === "Société"){
        block.style.display = "block";
    }else{
        block.style.display = "none";
    }
}

document.querySelectorAll('input[name="type"]').forEach(el=>{
    el.addEventListener("change", toggleRaisonSociale);
});

document.addEventListener("DOMContentLoaded", toggleRaisonSociale);

const telInput = document.querySelector("#telephone");
const whatsappInput = document.querySelector("#whatsapp");

const itiTel = window.intlTelInput(telInput, {
    initialCountry: "auto",
    nationalMode: false,
    preferredCountries: ["tg","ci","sn","fr"],
    geoIpLookup: function(callback) {
        fetch("https://ipapi.co/json")
            .then(res => res.json())
            .then(data => callback(data.country_code.toLowerCase()))
            .catch(() => callback("tg"));
    },
    utilsScript:
    "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
});

const itiWhatsapp = window.intlTelInput(whatsappInput, {
    initialCountry: "auto",
    nationalMode: false,
    preferredCountries: ["tg","ci","sn","fr"],
    utilsScript:
    "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
});

</script>
@endpush
