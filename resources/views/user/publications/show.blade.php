@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="card shadow-sm">
    <div class="card-body">

        <h4 class="mb-3">
            {{ $dispositif->type_dispositif->nom }}
        </h4>

        <p class="text-muted">
            {{ $dispositif->type_dispositif->categorie->nom }}
        </p>

        <span class="badge bg-info mb-3">
            {{ ucfirst($dispositif->statut) }}
        </span>

        <p>{{ $dispositif->description }}</p>

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

        <div class="mt-4">
            <a href="{{ route('user.dispositifs.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </div>

    </div>
</div>

@endsection
