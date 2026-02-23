@extends('layouts.app')

@section('nav-bar')
{{-- Navbar masquée --}}
@endsection

@section('main-content')
<div class="container-fluid vh-100">
    <div class="row h-100">

        {{-- Image à gauche --}}
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center
                    bg-login-image">
            <div class="text-white text-center px-5">
                <h1 class="fw-bold mb-3">Plateforme BTP</h1>
                <p class="lead">
                    Gérez vos équipements, locations et utilisateurs en toute sécurité
                </p>
            </div>
        </div>

        {{-- Formulaire --}}
        <div class="col-md-6 d-flex align-items-center justify-content-center bg-body">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <div class="fs-1">🔐</div>
                            <h4 class="fw-bold">Connexion</h4>
                            <small class="text-muted">
                                Entrez votre code d’accès
                            </small>
                        </div>

                        {{-- Erreurs --}}
                        @if($errors->any())
                            <div class="alert alert-danger small">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email ou pseudo --}}
                            <div class="mb-3">
                                <label class="form-label">Email ou pseudo</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text"
                                        name="login"
                                        value="{{ old('login') }}"
                                        class="form-control"
                                        placeholder="email@exemple.com ou BTP-001"
                                        required autofocus>
                                </div>
                            </div>

                            {{-- Mot de passe --}}
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="••••••••"
                                           required>
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="togglePassword()">
                                        <i id="eyeIcon" class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Options --}}
                            <div class="d-flex justify-content-between mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="remember"
                                           id="remember">
                                    <label class="form-check-label" for="remember">
                                        Se souvenir
                                    </label>
                                </div>

                                <a href="{{-- route('password.request') --}}"
                                   class="small text-decoration-none">
                                    Mot de passe oublié ?
                                </a>
                            </div>

                            <button class="btn btn-primary w-100 py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Se connecter
                            </button>
                        </form>

                    </div>
                </div>
        </div>

    </div>
</div>

@endsection
