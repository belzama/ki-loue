@extends(auth()->user()->role == 'Admin' ? 'layouts.admin' : 'layouts.user') {{-- Corrigé 'guest' en 'user' car un guest n'a pas de dispositifs --}}

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
            <div class="col-md-5">
                <label class="form-label font-weight-bold">Catégorie</label>
                <select id="categorie_id" 
                        name="categorie_id" 
                        class="form-select">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label font-weight-bold">Type de dispositif</label>
                <select id="types_dispositif_id" 
                        name="types_dispositif_id" 
                        data-selected="{{ request('types_dispositif_id') }}"
                        class="form-select">
                    <option value="">-- Tous les types --</option>
                    {{-- Les options seront chargées par AJAX ou via le script --}}
                </select>
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- 📋 LISTE DES DISPOSITIFS --}}
<div class="row g-4">
    @forelse($dispositifs as $dispositif)
        <div class="col-sm-6 col-md-4">
            <div class="card h-100 shadow-sm border-0">
                {{-- Photo principale --}}
                <div class="position-relative">
                    @if($dispositif->main_photo)
                        <img src="{{ asset('storage/'.$dispositif->main_photo->path) }}" class="card-img-top" style="height:200px; object-fit:cover;" alt="Photo">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    
                    {{-- Badge Statut sur l'image --}}
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge {{ $dispositif->statut === 'Actif' ? 'bg-success' : ($dispositif->statut === 'Suspendu' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ $dispositif->statut }}
                        </span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-bold">{{ $dispositif->type_dispositif->categorie->nom ?? '-' }}</small>
                        <h6 class="mb-0">{{ $dispositif->type_dispositif->nom ?? '-' }}</h6>
                    </div>
                    
                    <p class="card-text text-muted small mb-3">
                        {{ Str::limit($dispositif->designation, 50) }} 
                        @if($dispositif->numero_immatriculation)
                            <span class="badge bg-light text-dark border"># {{ $dispositif->numero_immatriculation }}</span>
                        @endif
                    </p>

                    {{-- Actions --}}
                    <div class="mt-auto pt-3 border-top d-flex justify-content-between">
                        <div class="btn-group">
                            <a href="{{ route('user.dispositifs.show', $dispositif) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('user.dispositifs.edit', $dispositif) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('user.publications.createByDispositif', $dispositif) }}" class="btn btn-sm btn-outline-success" title="Publier">
                                <i class="bi bi-megaphone"></i>
                            </a>
                        </div>

                        <form action="{{ route('user.dispositifs.destroy', $dispositif) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Aucun dispositif trouvé.</p>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $dispositifs->withQueryString()->links() }}
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorieSelect = document.getElementById('categorie_id');
    const typeSelect = document.getElementById('types_dispositif_id');
    const selectedType = typeSelect.getAttribute('data-selected');

    function loadTypes(categorieId, selectedId = null) {
        if (!categorieId) {
            typeSelect.innerHTML = '<option value="">-- Tous les types --</option>';
            return;
        }

        // Appel AJAX vers votre route (à adapter selon votre web.php)
        fetch(`/types_dispositif/by-categorie/${categorieId}`)
            .then(response => response.json())
            .then(data => {
                typeSelect.innerHTML = '<option value="">-- Tous les types --</option>';
                data.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = type.nom;
                    if (selectedId && type.id == selectedId) {
                        option.selected = true;
                    }
                    typeSelect.appendChild(option);
                });
            });
    }

    // Charger au changement
    categorieSelect.addEventListener('change', function() {
        loadTypes(this.value);
    });

    // Charger au démarrage si une catégorie est déjà sélectionnée (cas du retour de recherche)
    if (categorieSelect.value) {
        loadTypes(categorieSelect.value, selectedType);
    }
});
</script>
@endsection