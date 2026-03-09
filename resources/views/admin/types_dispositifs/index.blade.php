@extends('layouts.admin')

@section('content')
<h1>Types de dispositifs</h1>

<a href="{{ route('admin.types_dispositifs.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter un type
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
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
            <td>{{ $type->id }}</td>
            <td>{{ $type->nom }}</td>
            <td>{{ $type->categorie->nom ?? '' }}</td>
            <td>{{ number_format($type->tarif_min, 0) }}</td>
            <td>{{ number_format($type->tarif_max, 0) }}</td>
            <td>{{ number_format($type->nb_max_photo, 0) }}</td>
            <td>
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

