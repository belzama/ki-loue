@extends('layouts.admin')

@section('content')
<h1>Catégories</h1>

<a href="{{ route('admin.categories.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter une catégorie
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $c)
        <tr>
            <td>{{ $c->nom }}</td>
            <td>
                <a href="{{ route('admin.categories.edit',$c) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form method="POST" action="{{ route('admin.categories.destroy',$c) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer cette catégorie ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
