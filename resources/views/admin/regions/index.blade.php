@extends('layouts.admin')

@section('content')
<h1>Villes</h1>

<a href="{{ route('admin.regions.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter une région
</a>

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

{{-- $regionList->links() --}}
@endsection

