@extends('layouts.admin')

@section('content')
<h1>Pays</h1>

<a href="{{ route('admin.pays.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter un pays
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Continent</th>
            <th>Nom</th>
            <th>Code ISO</th>
            <th>Indicatif</th>
            <th>Devise</th>
            <th>Nationalité</th>
            <th>Langue</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pays_list as $pays)
        <tr>
            <td>{{ $pays->id }}</td>
            <td>{{ $pays->continent->nom }}</td>
            <td>{{ $pays->nom ?? '' }}</td>
            <td>{{ $pays->code }}</td>
            <td>{{ $pays->indicatif }}</td>
            <td>{{ $pays->devise->code }}</td>
            <td>{{ $pays->nationalite }}</td>
            <td>{{ $pays->langue_officielle }}</td>
            <td>
                <a href="{{ route('admin.pays.edit', $pays) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form action="{{ route('admin.pays.destroy', $pays) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer ce pays ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- $pays_list->links() --}}
@endsection

