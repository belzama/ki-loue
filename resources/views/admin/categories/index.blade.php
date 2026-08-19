@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-truck me-2"></i> Catégories</h4>
    <a href="{{ route('admin.categories.create') }}"
        class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Ajouter une catégorie
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3">
    @foreach($categories as $c)
    <div class="col-6 col-md-4 col-lg-3">
        <div class="card h-100">
            @if($c->image_link)
                <img src="{{ asset('storage/'.$c->image_link) }}"
                     class="card-img-top" style="height: 140px; object-fit: cover;"
                     alt="{{ $c->nom }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center"
                     style="height: 140px;">
                    <i class="bi bi-image text-muted fs-2"></i>
                </div>
            @endif

            <div class="card-body d-flex flex-column">
                <h6 class="card-title mb-3">{{ $c->nom }}</h6>

                <div class="mt-auto d-flex gap-2">
                    <a href="{{ route('admin.categories.edit',$c) }}"
                        class="btn btn-sm btn-warning flex-fill"
                        title="Modifier">
                        <i class="bi bi-pencil-square"></i> Modifier
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy',$c) }}" class="flex-fill">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger w-100"
                            onclick="return confirm('Supprimer cette catégorie ?')" title="Supprimer">
                            <i class="bi bi-trash-fill"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($categories->isEmpty())
    <p class="text-muted">Aucune catégorie pour le moment.</p>
@endif
@endsection