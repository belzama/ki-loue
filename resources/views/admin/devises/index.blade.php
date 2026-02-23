@extends('layouts.admin')

@section('content')
<h1>Catégories</h1>

<a href="{{ route('admin.devises.create') }}" 
    class="btn btn-primary mb-3 bi bi-plus-lg">
    Ajouter une devise
</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Code</th>
            <th>Libellé</th>
            <th>Symbol</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($devises as $d)
        <tr>
            <td>{{ $d->code }}</td>
            <td>{{ $d->libelle }}</td>
            <td>{{ $d->symbol }}</td>
            <td>
                <a href="{{ route('admin.devises.edit',$d) }}" 
                    class="btn btn-sm btn-warning bi bi-pencil-square"
                    title="Modifier">
                </a>
                <form method="POST" action="{{ route('admin.devises.destroy',$d) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger bi bi-trash-fill"
                        onclick="return confirm('Supprimer cette devise ?')" title="Supprimer">
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
