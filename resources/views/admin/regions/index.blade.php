@extends('layouts.admin')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <i class="bi bi-map page-header-icon"></i>
        <div>
            <h1 class="page-title">Régions ({{ $regions->total() }})</h1>
        </div>
    </div>
    
    <a href="{{ route('admin.regions.create') }}" class="btn btn-add-el">
        <i class="bi bi-plus-lg me-2"></i>Ajouter une région
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pays</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($regions as $region)
        <tr>
            <td>{{ $region->id }}</td>
            <td>{{ $region->pays->nom }}</td>
            <td>{{ $region->nom ?? '' }}</td>
            <td>
                <a href="{{ route('admin.regions.edit', $region) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer cette region ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $regions->links() }}
</div>
@endsection

