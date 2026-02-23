@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-truck me-2"></i> Mes dispositifs</h4>
    <a href="{{ route('user.dispositifs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Ajouter un dispositif
    </a>
</div>

{{-- 🔍 FILTRE --}}
<div class="card shadow-sm mb-4 p-3">
    <form method="GET" action="{{ route('user.dispositifs.index') }}">
        <div class="row g-3 align-items-end">

            <div class="col-md-4">
                <label class="form-label">Catégorie</label>
                <select name="categorie_id" id="categorie_id" class="form-select">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Type de dispositif</label>
                <select name="type_dispositif_id" id="type_dispositif_id" class="form-select">
                    <option value="">-- Tous les types --</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" data-categorie="{{ $type->categorie_id }}"
                            {{ request('type_dispositif_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <button class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>

        </div>
    </form>
</div>

{{-- 📋 LISTE DES DISPOSITIFS EN CARDS --}}
<div class="row g-4">

    @forelse($dispositifs as $dispositif)
        @php
            $photo = $dispositif->main_photo;
        @endphp

        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="card h-100 shadow-sm">

                {{-- Photo principale --}}

                @if($photo)
                    <div class="position-relative">
                        <img src="{{ asset('storage/'.$photo->path) }}" class="card-img-top" style="height:180px; object-fit:cover;" alt="Photo du dispositif">
                    </div>
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                        <span class="text-muted">Pas de photo</span>
                    </div>
                @endif

                <div class="card-body d-flex flex-column">

                    {{-- Catégorie / Type --}}
                    <div class="mb-2">
                        <span class="badge bg-secondary text-truncate" style="width:100%">
                            {{ $dispositif->type_dispositif->categorie->nom ?? '-' }}
                        </span>
                        <span class="badge bg-primary text-truncate" style="width:100%">
                            {{ $dispositif->type_dispositif->nom ?? '-' }}
                        </span>
                    </div>
                    {{-- Designation --}}
                    <p class="card-text text-truncate mb-2">{{ Str::limit($dispositif->designation, 80) }} {{ $dispositif->numero_immatriculation }}</p>

                    {{-- Statut --}}
                    <span class="badge 
                        @if($dispositif->statut === 'actif') bg-success
                        @elseif($dispositif->statut === 'suspendu') bg-warning
                        @else bg-secondary @endif mb-2">
                        {{ ucfirst($dispositif->statut) }}
                    </span>

                    {{-- Actions --}}
                    <div class="mt-auto d-flex justify-content-between flex-wrap">
                        <a href="{{ route('user.dispositifs.show', $dispositif) }}" class="btn btn-sm btn-outline-primary" title="Voir plus">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('user.dispositifs.edit', $dispositif) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        {{-- Bouton Publier --}}
                        <a href="{{ route('user.publications.createByDispositif', $dispositif) }}" 
                        class="btn btn-sm btn-outline-success" title="Publier">
                            <i class="bi bi-upload"></i>
                        </a>

                        <form action="{{ route('user.dispositifs.destroy', $dispositif) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')" title="Supprimer">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @empty
        <div class="col-12 text-center py-5 text-muted">
            Aucun dispositif trouvé
        </div>
    @endforelse

</div>

{{-- Pagination --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $dispositifs->withQueryString()->links() }}
</div>

{{-- JS : filtrage type par catégorie --}}
<script>
    function filtrerTypes() {
        const categorieId = document.getElementById('categorie_id').value;
        const typeSelect = document.getElementById('type_dispositif_id');

        [...typeSelect.options].forEach(option => {
            if (!option.value) return;
            option.hidden = !categorieId || option.dataset.categorie === categorieId ? false : true;
        });

        if (typeSelect.selectedOptions.length && typeSelect.selectedOptions[0].hidden) {
            typeSelect.value = '';
        }
    }

    document.getElementById('categorie_id').addEventListener('change', filtrerTypes);
    document.addEventListener('DOMContentLoaded', filtrerTypes);
</script>

@endsection
