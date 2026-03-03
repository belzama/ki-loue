@extends('layouts.app')

@section('nav-bar')
    @auth
        @include('partials.user-connected-navbar')
    @endauth

    @guest
        @include('partials.welcome-navbar')
    @endguest
@endsection

@section('main-content')
<div class="container my-4">
    <h2>Réserver le dispositif : {{ $publication->dispositif->designation }}</h2>

    {{-- Message de succès --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Affichage des erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        {{-- Carrousel photos à gauche --}}
        <div class="col-md-4 mb-3">
            @if($publication->dispositif->photos && $publication->dispositif->photos->count() > 0)
                <div id="carouselPhotos" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($publication->dispositif->photos as $index => $photo)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $photo->path) }}"
                                     class="d-block w-100 rounded shadow-sm" alt="Photo {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPhotos" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Précédent</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselPhotos" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Suivant</span>
                    </button>
                </div>
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                     style="height:300px;">
                    Pas de photo disponible
                </div>
            @endif
        </div>

        {{-- Formulaire à droite --}} 
        <div class="col-md-8">
            <form action="{{ route('reservations.store', $publication) }}" method="POST">
                @csrf

                {{-- Nom et prénom 
                <div class="mb-3">
                    <label class="form-label">Nom et prénom</label>
                    <input type="text" name="nom_prenom" class="form-control" value="{{ old('nom_prenom') }}" required>
                </div>--}}

                {{-- Email et téléphone sur la même ligne
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" required>
                    </div>
                </div> --}}

                {{-- Date et durée souhaitées sur la même ligne --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date souhaitée</label>
                        <input type="date" name="date_demandee" class="form-control" value="{{ old('date_demandee') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Durée souhaitée (jours)</label>
                        <input type="number" name="duree_demandee" class="form-control" value="{{ old('duree_demandee', 1) }}" min="1" required>
                    </div>
                </div>

                {{-- Message optionnel --}}
                <div class="mb-3">
                    <label class="form-label">Message (optionnel)</label>
                    <textarea name="message" class="form-control">{{ old('message') }}</textarea>
                </div>

                {{-- Boutons --}}
                <div class="mt-4">
                    {{-- ACTION PRINCIPALE --}}
                    
                    <button type="submit"
                            name="action"
                            value="whatsapp"
                            class="btn btn-success w-100 mb-2">
                        <i class="bi bi-whatsapp me-2"></i>
                        Contacter sur WhatsApp
                    </button>

                    <div class="row g-2">

                        {{-- APPEL (mobile uniquement) --}}
                        <div class="col-6 d-md-none">
                            <button type="submit"
                                    name="action"
                                    value="call"
                                    class="btn btn-outline-primary w-100">
                                <i class="bi bi-telephone"></i>
                                Appeler
                            </button>
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <button type="submit"
                                    name="action"
                                    value="email"
                                    class="btn btn-outline-dark w-100">
                                <i class="bi bi-envelope"></i>
                                Email
                            </button>
                        </div>

                        {{-- ANNULER --}}
                        <div class="col-md-6">
                            <a href="{{ url()->previous() }}"
                            class="btn btn-secondary w-100">
                                Annuler
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
