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

                        {{-- CODE SPONSOR --}}
                        <div class="mb-3">
                            <label class="form-label">Code sponsor (optionnel)</label>
                            <input type="text"
                                   name="ref_code"
                                   value="{{ old('ref_code', request('ref')) }}"
                                   class="form-control">
                        </div>

                        {{-- NOM --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Nom & prénom(s) / Raison sociale
                                <span class="text-danger">*</span>
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

                        {{-- PAYS + CONTACT --}}
                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    Pays <span class="text-danger">*</span>
                                </label>

                                <select name="pays_id" class="form-select" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($pays as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('pays_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pays_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Contact WhatsApp</label>
                                <input type="tel"
                                       name="contact"
                                       value="{{ old('contact') }}"
                                       class="form-control">
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
