<div class="container my-5">

    {{-- FILTRES --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET">
                <div class="row align-items-start">

                    <!-- CHAMPS -->
                    <div class="col-lg-9">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label">Pays</label>
                                <select id="pays_id" name="pays_id"
                                        data-child="region_id"
                                        data-url="{{ url('regions/by-pays') }}/"
                                        class="form-select">
                                    <option value="" data-division="Région" data-sous-division="Préfecture">Tous</option>
                                    @foreach($pays as $p)
                                        <option value="{{ $p->id }}" 
                                            data-division="{{ $p->libelle_division }}"
                                            data-sous-division="{{ $p->libelle_sous_division }}"
                                            {{ (request('pays_id') == $p->id || (isset($country) && $country->id == $p->id)) ? 'selected' : '' }}>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label id="label_division" class="form-label">{{ $country?->libelle_division ?? 'Région' }}</label>
                                <select id="region_id" name="region_id" 
                                        data-child="departement_id"
                                        data-url="{{ url('departements/by-region') }}/"
                                        data-selected="{{ request('region_id') }}"
                                        class="form-select">
                                    <option value="">Toutes</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label id="label_sous_division" class="form-label">{{ $country?->libelle_sous_division ?? 'Préfecture' }}</label>
                                <select id="departement_id" name="departement_id" 
                                        data-selected="{{ request('departement_id') }}"
                                        class="form-select">
                                    <option value="">Toutes</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Catégorie de matériel</label>
                                <select id="categorie_id" 
                                        name="categorie_id"
                                        data-child="types_dispositif_id"
                                        data-url="{{ url('types_dispositif/by-categorie') }}/"
                                        class="form-select">
                                    <option value="">Tous</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" 
                                            {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label">Type de matériel</label>
                                <select id="types_dispositif_id" 
                                        name="types_dispositif_id" 
                                        data-selected="{{ request('types_dispositif_id') }}"
                                        class="form-select">
                                    <option value="">Tous</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- BOUTONS -->
                    <div class="col-lg-3">
                        <div class="d-grid gap-2 mt-3 mt-lg-0">
                            <button type="submit" class="btn btn-dark">
                                Rechercher
                            </button>

                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                Réinitialiser
                            </a>

                            <a href="#" class="btn btn-outline-primary">
                                Plus de filtres
                            </a>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- LISTE --}}
    <div class="row g-4">
        @forelse($publications as $publication)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm">

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

                    {{-- Contenu --}}
                    <div class="card-body">                        
                        {{-- Catégorie / Type --}}
                        <div class="mb-2">
                            {{ $publication->dispositif->designation ?? '-' }}
                        </div>
                        
                        {{-- localisation --}}
                        <div class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i>
                            {{ $publication->departement->nom ?? '' }},
                            {{ $publication->departement->region->nom ?? '' }},
                            {{ $publication->departement->region->pays->nom ?? '' }}
                        </div>
                        
                        {{-- prix --}}
                        <p class="fw-bold text-success">
                        <div class="mt-2 fs-5 fw-bold text-success">
                            {{ number_format($publication->tarif_location,0,',',' ') }}
                            {{ $publication->devise->symbol ?? '' }}
                            <span class="text-muted fs-6">/ jour</span>
                        </div>
                        </p>

                        {{-- Actions --}}
                        <div class="d-grid gap-2">
                            <a href="{{ route('publications.show', $publication) }}"
                            class="btn btn-outline-primary">
                                Voir détails
                            </a>                            

                            <div class="d-grid gap-2">
                                <button type="button"
                                        class="btn btn-success contact-btn"
                                        data-url="{{ route('reservations.store', $publication->id) }}">
                                    Contacter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('partials.contact-modal')

        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aucune publication trouvée
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $publications->links() }}
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/dependent-select.js') }}"></script>
    <script src="{{ asset('js/contact-modal.js') }}"></script>
@endpush