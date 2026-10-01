@extends('layouts.admin')

@section('content')

{{-- PAGE TITLE --}}
<div class="page-header">
    <div class="page-header-left">
        <i class="bi bi-globe-americas page-header-icon"></i>
        <div>
            <h1 class="page-title">Pays ({{ $pays_list->total() }})</h1>
        </div>
    </div>
    
    <a href="{{ route('admin.pays.create') }}" class="btn btn-add-el">
        <i class="bi bi-plus-lg me-2"></i>Ajouter un pays
    </a>
</div>

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
                    title="Modifier"
                    data-waiting
                    data-waiting-message="Ouverture du pays...">
                </a>
                <form action="{{ route('admin.pays.destroy', $pays) }}" method="POST" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer ce pays ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<div class="mt-4">
    {{ $pays_list->links() }}
</div>
@endsection

