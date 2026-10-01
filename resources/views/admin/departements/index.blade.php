@extends('layouts.admin')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <i class="bi bi-building page-header-icon"></i>
        <div>
            <h1 class="page-title">Sous-régions ({{ $departements->total() }})</h1>
        </div>
    </div>
    
    <a href="{{ route('admin.departements.create') }}" class="btn btn-add-el">
        <i class="bi bi-plus-lg me-2"></i>Ajouter une sous-région
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Région</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($departements as $departement)
        <tr>
            <td>{{ $departement->id }}</td>
            <td>{{ $departement->region->pays->nom }} - {{ $departement->region->nom }}</td>
            <td>{{ $departement->nom ?? '' }}</td>
            <td>
                <a href="{{ route('admin.departements.edit', $departement) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form action="{{ route('admin.departements.destroy', $departement) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer sous-région ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $departements->links() }}
@endsection

