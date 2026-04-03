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

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Tarif min</th>
            <th>Tarif max</th>
            <th>Nb. max photo</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($types as $type)
        <tr>
            <td>{{ $type->nom }}</td>
            <td>{{ $type->categorie->nom ?? '' }}</td>
            <td>{{ number_format($type->tarif_min, 0) }}</td>
            <td>{{ number_format($type->tarif_max, 0) }}</td>
            <td width="60px">{{ number_format($type->nb_max_photo, 0) }}</td>
            <td width="100px">
                <a href="{{ route('admin.types_dispositifs.edit', $type) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form action="{{ route('admin.types_dispositifs.destroy', $type) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer ce type ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $types->links() }}
@endsection

