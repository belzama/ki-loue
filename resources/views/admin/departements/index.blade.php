@extends('layouts.admin')

@section('content')
<h1>Préfectures/Départements</h1>

<a href="{{ route('admin.departements.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter préfecture/département
</a>

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
                        onclick="return confirm('Supprimer préfecture/département ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- $departementList->links() --}}
@endsection

