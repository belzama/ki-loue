@extends('layouts.guest')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h4 class="mb-3">
            {{ $dispositif->type_dispositif->nom }}
            {{ $dispositif->designation }}
            {{ $dispositif->numero_immatriculation }}
            <span class="badge bg-info mb-3">
                {{ ucfirst($dispositif->etat) }}
            </span>
        </h4>

        {{-- ===== 4. GALERIE PHOTOS ===== --}}
        <div class="card mb-3 shadow-sm">

            <div class="card-body">
                <div class="lightbox-gallery row g-2">
                    @forelse($dispositif->photos as $index => $photo)
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
        
        {{-- Container pour les paramètres dynamiques --}}
        @if($dispositif->params->count())
            <h5 class="mt-3">Caractéristiques</h5>
            <div class="row">
                @foreach($dispositif->params as $param)
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted">{{ ucfirst($param->typeParam->label ?? $param->name) }} ({{ $param->typeParam->numeric_value_unit ?? '' }})</small>
                            <div class="fw-bold">
                                {{ $param->value }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


        <div class="mt-4">
            <a href="{{ route('user.dispositifs.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </div>

    </div>
</div>

@include('partials.photo-viewer')
@endsection
