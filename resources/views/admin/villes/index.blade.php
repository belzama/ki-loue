@extends('layouts.admin')

@section('content')
<h1>Pays</h1>

<a href="{{ route('admin.villes.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter une ville/préfecture
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pays/Région</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($villes as $ville)
        <tr>
            <td>{{ $ville->id }}</td>
            <td>{{ $ville->pays->nom }}</td>
            <td>{{ $ville->nom ?? '' }}</td>
            <td>
                <a href="{{ route('admin.villes.edit', $ville) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form action="{{ route('admin.villes.destroy', $ville) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer cette ville ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- $villeList->links() --}}
@endsection

