@extends('layouts.guest')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h4 class="mb-3">
            {{ $dispositif->type_dispositif->nom }}
            {{ $dispositif->designation }}
            {{ $dispositif->numero_immatriculation }}
            <span class="badge bg-info mb-3">
                {{ ucfirst($dispositif->statut) }}
            </span>
        </h4>
        <hr>

        <div class="row g-2">
            @forelse($dispositif->photos as $photo)
                <div class="col-md-3">
                    <img src="{{ asset('storage/'.$photo->path) }}"
                         class="img-fluid rounded shadow-sm">
                </div>
            @empty
                <p class="text-muted">Aucune photo</p>
            @endforelse
        </div>
        <hr>
        {{-- Container pour les paramètres dynamiques --}}
        @if($dispositif->params->count())
            <h5 class="mt-3">Caractéristiques</h5>
            <div class="row">
                @foreach($dispositif->params as $param)
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted">{{ ucfirst($param->typeParam->label ?? $param->name) }}</small>
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

@endsection
