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

    {{-- ===== Titre + Statut ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            Publication — {{ $publication->dispositif->designation }}            

            <span class="badge
                @if($publication->active === 0) bg-warning
                @elseif($publication->active === 1) bg-success
                @else bg-danger
                @endif">
                @if($publication->active === 0)
                    Inactif
                @elseif($publication->active === 1)
                    Actif
                @else
                    Inconnu
                @endif
            </span>
        </h3>

        <div class="d-grid gap-2">
            <button type="button"  
                    class="btn btn-success contact-btn"
                    data-url="{{ route('reservations.store', $publication->id) }}">
                Contacter
            </button>
        </div>
        @include('partials.contact-modal')

    </div>

    {{-- ===== 3. INFOS DISPOSITIF ===== --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-dark text-white">
            Informations dispositif
        </div>

        <div class="card-body">
            <p><strong>Type :</strong>
                {{ $publication->dispositif->type_dispositif->nom }}
            </p>

            <p><strong>Lieu :</strong>
                {{ $publication->ville->nom }},
                {{ $publication->ville->region->pays->nom }}
            </p>

            {{-- Paramètres --}}
            @if($publication->dispositif->params->count())
                <hr>
                <h6 class="mb-3">Caractéristiques</h6>

                <div class="row">
                    @foreach($publication->dispositif->params as $param)
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2 bg-light">
                                <small class="text-muted">
                                    {{ $param->typeParam->label ?? ucfirst($param->name) }}
                                </small>
                                <div class="fw-bold">
                                    {{ $param->value }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


    {{-- ===== 4. GALERIE PHOTOS ===== --}}
    <div class="card mb-3 shadow-sm">

        <div class="card-body">
            <div class="lightbox-gallery row g-2">
                @forelse($publication->dispositif->photos as $index => $photo)
                    <div class="col-md-3 mb-3">
                        <img src="{{ asset('storage/' . $photo->path) }}"
                        class="img-fluid rounded shadow-sm lightbox-item"
                        style="height:150px; object-fit:cover; cursor:pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#photoModal"
                        data-index="{{ $index }}">
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">
                        Aucune photo disponible
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bouton réservation --}}
    <div class="mt-4">
        <a href="{{ route('reservations.create', $publication) }}" class="btn btn-primary">
            Demander une réservation
        </a>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            Retour
        </a>
    </div>
</div>

@include('partials.photo-viewer')

@endsection

@push('scripts')
    <script src="{{ asset('js/contact-modal.js') }}"></script>
@endpush