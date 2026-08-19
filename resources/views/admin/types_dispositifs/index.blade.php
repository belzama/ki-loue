@extends('layouts.admin')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-cpu me-2"></i> Types de matériels ({{ $types->total() }})</h4>
    <a href="{{ route('admin.types_dispositifs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Ajouter un type de matériel
    </a>
</div>

{{-- 🔍 FILTRE --}}
<div class="card shadow-sm mb-4 p-3">
    <form method="GET" action="{{ route('admin.types_dispositifs.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label font-weight-bold">Catégorie</label>
                <select id="categorie_id"
                        name="categorie_id"
                        class="form-select">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label>Nom du type</label>
                <input type="text" name="nom" class="form-control"
                    value="{{ old('nom', request('nom')) }}">
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary">
                    <i class="bi bi-search"></i>
                </button>
            </div>

        </div>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3">
    @foreach($types as $type)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
            @if($type->image_link)
                <img src="{{ asset('storage/'.$type->image_link) }}"
                     class="card-img-top" style="height: 140px; object-fit: cover;"
                     alt="{{ $type->nom }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center"
                     style="height: 140px;">
                    <i class="bi bi-image text-muted fs-2"></i>
                </div>
            @endif

            <div class="card-body d-flex flex-column">
                <h6 class="card-title mb-1">{{ $type->nom }}</h6>
                <div class="text-muted small mb-2">{{ $type->categorie->nom ?? '' }}</div>

                <ul class="list-unstyled small mb-3">
                    <li><strong>Tarif :</strong> {{ number_format($type->tarif_min, 0, ',', ' ') }} - {{ number_format($type->tarif_max, 0, ',', ' ') }}</li>
                    <li><strong>Photos max :</strong> {{ number_format($type->nb_max_photo, 0) }}</li>
                </ul>

                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('admin.types_dispositifs.edit', $type) }}"
                        class="btn btn-sm btn-warning flex-fill"
                        title="Modifier">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                    <form action="{{ route('admin.types_dispositifs.destroy', $type) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100"
                            onclick="return confirm('Supprimer ce type ?')" title="Supprimer">
                            <i class="bi bi-trash-fill"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($types->isEmpty())
    <p class="text-muted mt-3">Aucun type ne correspond à ce filtre.</p>
@endif

<div class="mt-3">
    {{ $types->links() }}
</div>
@endsection