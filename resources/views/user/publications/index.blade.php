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
                    <label class="form-label fw-semibold">Catégorie de matériel</label>
                    <select id="categorie_id" 
                            name="categorie_id" 
                            data-child="types_dispositif_id"
                            data-url="{{ url('types_dispositif/by-categorie') }}/"
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
                            data-selected="{{ request('departement_id') }}"
                            class="form-select shadow-sm">
                        <option value="">Tous</option>
                    </select>
                </div>

            </div>

            {{-- Bloc 2 --}}
            <div class="row g-4 mb-3">

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Matériel</label>
                    <input type="text"
                           name="designation"
                           value="{{ request('designation') }}"
                           class="form-control shadow-sm"
                           placeholder="🔍 Rechercher par désignation...">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Etat</label>
                    <select name="etat" class="form-select shadow-sm">
                        <option value="">Tous</option>
                        <option value="Neuf" {{ request('etat') === 'Neuf' ? 'selected' : '' }}>
                            🟢 Neuf
                        </option>
                        <option value="Bon" {{ request('etat') === 'Bon' ? 'selected' : '' }}>
                            🔵 Bon
                        </option>
                        <option value="Révisé" {{ request('etat') === 'Révisé' ? 'selected' : '' }}>
                            🟡 Révisé
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
                            data-child="departement_id"
                            data-url="{{ url('departements/by-region') }}/"
                            data-selected="{{ request('region_id') }}"
                            class="form-select shadow-sm">
                        <option value="">Toutes</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Departement</label>
                    <select id="departement_id"
                            name="departement_id" 
                            data-selected="{{ request('departement_id') }}"
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

        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 publication-card">

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

                    <button class="carousel-control-prev"
                        type="button"
                        data-bs-target="#carousel{{ $publication->id }}"
                        data-bs-slide="prev">

                        <span class="carousel-control-prev-icon"></span>

                    </button>

                    <button class="carousel-control-next"
                            type="button"
                            data-bs-target="#carousel{{ $publication->id }}"
                            data-bs-slide="next">

                        <span class="carousel-control-next-icon"></span>

                    </button>

                @endif
                
                {{-- Badge Statut sur l'image --}}
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge {{ $publication->dispositif->etat === 'Neuf' ? 'bg-success' : ($publication->dispositif->etat === 'Bon' ? 'bg-primary' : 'bg-warning') }}">
                        {{ $publication->dispositif->etat }}
                    </span>
                </div>
            </div>

            <div class="card-body d-flex flex-column">
            <div class="mb-2">
                <small class="text-muted text-uppercase fw-bold">{{ $publication->dispositif->type_dispositif->categorie->nom ?? '-' }}</small>
                <h6 class="mb-0">{{ $publication->dispositif->type_dispositif->nom ?? '-' }}</h6>
            </div>
            
            <p class="card-text text-muted small mb-3">
                {{ Str::limit($publication->dispositif->designation, 150) }} 
                @if($publication->dispositif->numero_immatriculation)
                    <span class="badge bg-light text-dark border"># {{ $publication->dispositif->numero_immatriculation }}</span>
                @endif
            </p>

            {{-- localisation --}}
            <small class="text-muted mb-2">
                <i class="bi bi-geo-alt"></i>
                {{ $publication->departement->nom ?? '' }},
                {{ $publication->departement->region->pays->nom ?? '' }}
            </small>

        {{-- prix --}}
        <div class="mt-2 fs-5 fw-bold text-success">
            {{ number_format($publication->tarif_location,0,',',' ') }}
            {{ $publication->devise->symbol ?? '' }}
            <span class="text-muted fs-6">/ jour</span>
        </div>

        {{-- période --}}
            <small class="text-muted">
            <i class="bi bi-calendar"></i>
            Du {{ $publication->date_debut->format('d/m/Y') }}
            au {{ $publication->date_fin->format('d/m/Y') }}

            {{-- badges --}}
            <div class="mb-2">
                @php
                    $statutText = $publication->active ? 'Encours' : 'Expirée';
                    $statutColor = $publication->active ? 'bg-success' : 'bg-danger';
                @endphp

                <span class="badge {{ $statutColor }}">
                    {{ $statutText }}
                </span>

            </div>
        </small>

        {{-- actions --}}
        <div class="mt-auto pt-3 d-flex gap-2">

            <a href="{{ route('user.publications.edit', $publication) }}"
                class="btn btn-sm btn-outline-warning w-50">

                <i class="bi bi-pencil-square"></i> Modifier

            </a>

            <form action="{{ route('user.publications.destroy', $publication) }}"
                    method="POST"
                    class="w-50"
                    onsubmit="return confirm('Supprimer cette publication ?')">

                @csrf
                @method('DELETE')

                <button class="btn btn-sm btn-outline-danger">

                    <i class="bi bi-trash-fill" title="Supprimer"></i> 

                </button>

            </form>

        </div>

    </div>
    </div>
</div>

@empty

<div class="col-12 text-center text-muted py-5">

<i class="bi bi-folder-x fs-1"></i>
<br>
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