@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-journal-text me-2"></i> Mes publications</h4>
    <a href="{{ route('user.publications.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouvelle publication
    </a>
</div>

{{-- 🔍 FILTRE AVANCÉ --}}
<div class="card border-0 shadow-lg mb-4 rounded-4">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold">
                <i class="bi bi-funnel-fill text-primary me-2"></i>
                Filtres de recherche
            </h5>
        </div>

        <form method="GET" action="{{ route('user.publications.index') }}">
            
            {{-- Bloc 1 --}}
            <div class="row g-4 mb-3">

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Catégorie</label>
                    <select id="categorie_id" 
                            name="categorie_id" 
                            data-child="types_dispositif_id"
                            data-url="{{ url('types_dispositif/by-categorie/') }}"
                            class="form-select shadow-sm">
                        <option value="">Toutes</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}"
                                {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-7">
                    <label class="form-label fw-semibold">Type</label>
                    <select id="types_dispositif_id" 
                            name="types_dispositif_id" 
                            data-selected="{{ request('ville_id') }}"
                            class="form-select shadow-sm">
                        <option value="">Tous</option>
                    </select>
                </div>

            </div>

            {{-- Bloc 2 --}}
            <div class="row g-4 mb-3">

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Dispositif</label>
                    <input type="text"
                           name="designation"
                           value="{{ request('designation') }}"
                           class="form-control shadow-sm"
                           placeholder="🔍 Rechercher par désignation...">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="statut" class="form-select shadow-sm">
                        <option value="">Tous</option>
                        <option value="1" {{ request('statut') === '1' ? 'selected' : '' }}>
                            🟢 Active
                        </option>
                        <option value="0" {{ request('statut') === '0' ? 'selected' : '' }}>
                            🔴 Inactif
                        </option>
                    </select>
                </div>

            </div>

            {{-- Bloc 3 --}}
            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pays</label>
                    <select id="pays_id" 
                            name="pays_id"
                            data-child="region_id"
                            data-url="{{ url('regions/by-pays') }}/"
                            class="form-select shadow-sm">
                        <option value="">Tous</option>
                        @foreach($pays as $p)
                            <option value="{{ $p->id }}"
                                {{ request('pays_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Région</label>
                    <select id="region_id"
                            name="region_id" 
                            data-child="ville_id"
                            data-url="{{ url('villes/by-region') }}/"
                            data-selected="{{ request('region_id') }}"
                            class="form-select shadow-sm">
                        <option value="">Toutes</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ville</label>
                    <select id="ville_id"
                            name="ville_id" 
                            data-selected="{{ request('ville_id') }}"
                            class="form-select shadow-sm">
                        <option value="">Toutes</option>
                    </select>
                </div>
            </div>

            {{-- Ligne boutons --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-end gap-3">

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search me-2"></i>
                            Rechercher
                        </button>

                        <a href="{{ url()->current() }}" 
                        class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Réinitialiser
                        </a>

                        <button type="button" 
                                class="btn btn-outline-dark px-4"
                                data-bs-toggle="collapse" 
                                data-bs-target="#moreFilters">
                            <i class="bi bi-sliders me-2"></i>
                            Plus de filtres
                        </button>

                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="row g-4">
    @forelse($publications as $publication)

        <div class="col-md-4">
            <div class="card shadow-sm h-100">

                {{-- Carousel photos --}}
                <div id="carousel{{ $publication->id }}" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        @forelse($publication->dispositif->photos as $index => $photo)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/'.$photo->path) }}"
                                    class="d-block w-100"
                                    style="height:220px; object-fit:cover;"
                                    alt="photo dispositif">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('images/no-image.png') }}"
                                    class="d-block w-100"
                                    style="height:220px; object-fit:cover;">
                            </div>
                        @endforelse

                    </div>

                    @if($publication->dispositif->photos->count() > 1)
                        <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel{{ $publication->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel{{ $publication->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>

                <div class="card-body d-flex flex-column">

                    <h6 class="fw-bold">
                        {{ $publication->dispositif->designation }}
                    </h6>

                    <small class="text-muted">
                        {{ $publication->ville->nom ?? '' }},
                        {{ $publication->ville->region->pays->nom ?? '' }}
                    </small>

                    <div class="mt-2">
                        <span class="badge bg-primary">
                            {{ $publication->dispositif->type_dispositif->nom ?? '' }}
                        </span>

                        <span class="badge {{ $publication->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $publication->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <div class="mt-3 fw-bold text-success">
                        {{ $publication->tarif_location }}
                        {{ $publication->devise->symbol ?? '' }}
                    </div>

                    <small class="text-muted">
                        Du {{ $publication->date_debut }} au {{ $publication->date_fin }}
                    </small>

                    <div class="mt-auto pt-3 d-flex justify-content-between">
                        <a href="{{ route('user.publications.edit', $publication) }}" 
                                class="btn btn-sm btn-outline-warning" 
                                title="Modifier"> 
                            <i class="bi bi-pencil-square"></i> Modifier
                        </a> 
                        <form action="{{ route('user.publications.destroy', $publication) }}" 
                                method="POST" style="display:inline-block;" 
                                onsubmit="return confirm('Supprimer cette publication ?')"> 
                            @csrf @method('DELETE') 
                            <button class="btn btn-sm btn-outline-danger"> 
                                <i class="bi bi-trash-fill"></i> Supprimer
                            </button> 
                        </form>
                    </div>

                </div>
            </div>
        </div>

    @empty
        <div class="col-12 text-center text-muted py-5">
            Aucune publication trouvée
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $publications->withQueryString()->links() }}
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/dependent-select.js') }}"></script>
@endsection