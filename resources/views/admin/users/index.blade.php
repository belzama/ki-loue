@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <i class="bi bi-people page-header-icon"></i>
        <div>
            <h1 class="page-title">Utilisateurs ({{ $users->total() }})</h1>
        </div>
    </div>
    
    <a href="{{ route('admin.users.create') }}" class="btn btn-add-el">
        <i class="bi bi-plus-lg me-2"></i>Ajouter un utilisateur
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">    
    <thead>
        <tr>
            <th>Code Parrainage</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Pays</th>
            <th>Actions</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($users as $u)
        <tr>
            <td>{{ $u->code }}</td>
            <td>{{ $u->nom }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->role }}</td>
            <td>{{ $u->pays->nom }}</td>
            <td>
                <a href="{{ route('admin.users.show', $u) }}" 
                    class="btn btn-sm btn-outline-primary bi bi-eye" 
                    title="Voir plus">
                </a>
                <a href="{{ route('admin.users.edit',$u) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form method="POST" action="{{ route('admin.users.destroy',$u) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer cet utilisateur ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
