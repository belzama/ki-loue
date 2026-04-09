@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-journal-text me-2"></i> Mes publications ({{$publications->total()}})</h4>
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
                           placeholder="Rechercher par désignation...">
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
            <div class="row g-4 mb-3">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Pays</label>
                    <select id="pays_id"
                            name="pays_id"
                            data-child="region_id"
                            data-url="{{ url('regions/by-pays') }}/"
                            class="form-select shadow-sm">
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
                    <label id="label_division" class="form-label fw-semibold">{{ $country?->libelle_division ?? 'Région' }}</label>
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
                    <label id="label_sous_division" class="form-label fw-semibold">{{ $country?->libelle_sous_division ?? 'Préfecture' }}</label>
                    <select id="departement_id"
                            name="departement_id"
                            data-selected="{{ request('departement_id') }}"
                            class="form-select shadow-sm">
                        <option value="">Toutes</option>
                    </select>
                </div>
            </div>

            {{-- Bloc 4 : Statut et Période de publication --}}
            <div class="row g-4 mb-3">
                {{-- Le Statut reste seul sur sa ligne ou partage la ligne --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Statut</label>
                    <select name="active" class="form-select shadow-sm">
                        <option value="">Tous</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>🟢 Encours</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>🟡 Expirée</option>
                    </select>
                </div>

                {{-- Groupe Période de publication --}}
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Période de publication</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text small">Du</span>
                                <input type="date" name="date_debut_filtre" value="{{ request('date_debut_filtre') }}" class="form-control shadow-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text small">Au</span>
                                <input type="date" name="date_fin_filtre" value="{{ request('date_fin_filtre') }}" class="form-control shadow-sm">
                            </div>
                        </div>
                    </div>
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

        @php
            $isExpired = $publication->date_fin->isPast();
            $isActive = $publication->active == 1 && !$isExpired;
        @endphp

        <div class="col-lg-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 publication-card">

                {{-- Container Image avec Carrousel --}}
                <div class="position-relative">
                    <div id="carousel{{ $publication->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @forelse($publication->dispositif->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$photo->path) }}"
                                         class="d-block w-100"
                                         style="height:240px; object-fit:cover;"
                                         alt="Matériel">
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/no-image.png') }}" class="d-block w-100" style="height:240px; object-fit:cover;">
                                </div>
                            @endforelse
                        </div>

                        @if($publication->dispositif->photos->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $publication->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $publication->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        @endif
                    </div>

                    {{-- Badges flottants --}}
                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-2">
                        {{-- Badge Statut --}}
                        <span class="badge shadow-sm {{ $isActive ? 'bg-success' : ($isExpired ? 'bg-warning' : 'bg-danger') }}">
                            <i class="bi {{ $isActive ? 'bi-check-circle' : ($isExpired ? 'bi-calendar-x' : 'bi-slash-circle') }} me-1"></i>
                            @if($isExpired)
                                Expirée
                            @elseif($isActive)
                                En cours
                            @else
                                Désactivée
                            @endif
                        </span>
                    </div>

                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge shadow-sm {{ $publication->dispositif->etat === 'Neuf' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $publication->dispositif->etat }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Catégorie et Titre --}}
                    <div class="mb-2">
                        <span class="text-primary fw-bold small text-uppercase tracking-wider">
                            {{ $publication->dispositif->type_dispositif->categorie->nom ?? 'Matériel' }}
                        </span>
                        <h5 class="card-title mt-1 fw-bold text-dark">
                            {{ $publication->dispositif->designation }}
                        </h5>
                    </div>

                    {{-- Détails techniques --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-geo-alt text-danger"></i>
                        <span class="text-muted small">
                            {{ $publication->departement->region->pays->libelle_division ?? '' }}
                            {{ $publication->departement->nom ?? '' }},
                            {{ $publication->departement->region->pays->nom ?? '' }}
                        </span>
                    </div>

                    {{-- Prix et Période --}}
                    <div class="d-flex justify-content-between align-items-end mt-auto pt-3 border-top">
                        <div class="price-tag">
                            <span class="text-success fw-bold fs-4">{{ number_format($publication->tarif_location,0,',',' ') }}</span>
                            <span class="text-success fw-semibold small">{{ $publication->devise->symbol ?? 'FCFA' }}</span>
                            <div class="text-muted x-small" style="font-size: 0.7rem;">PAR JOUR</div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i>Disponibilité</div>
                            <span class="fw-bold small text-dark">
                                {{ $publication->date_debut->format('d M') }} - {{ $publication->date_fin->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Date de publication --}}
                    <div class="mb-2">
                        <span class="text-danger fw-bold small tracking-wider">
                            Publié le {{ $publication->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card-footer bg-white border-0 p-4 pt-0 d-flex gap-2">
                    @if(!$isExpired && $publication->active)
                        <a href="{{ route('user.publications.edit', $publication) }}"
                        class="btn btn-outline-dark action-btn flex-grow-1 btn-sm">
                            <i class="bi bi-geo"></i> Modifier la localisation
                        </a>
                    @endif

                    <form action="{{ route('user.publications.destroy', $publication) }}"
                          method="POST"
                          class="d-flex gap-2"
                          onsubmit="return confirm('Confirmer l\'action ?')">
                        @csrf
                        @method('DELETE')

                        {{-- Un seul bouton dynamique selon le statut --}}
                        @if($isActive && !$isExpired)
                            <button type="submit" class="btn btn-outline-danger action-btn" title="Désactiver">
                                <i class="bi bi-power"></i>
                                <span>Désactiver</span>
                            </button>
                        @elseif(!$isActive && !$isExpired)
                            <button type="submit" class="btn btn-outline-success action-btn" title="Réactiver">
                                <i class="bi bi-play-fill"></i>
                                <span>Réactiver</span>
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="bg-light d-inline-block p-4 rounded-circle mb-3">
                <i class="bi bi- megaphone fs-1 text-muted"></i>
            </div>
            <h5 class="text-muted">Aucune publication trouvée</h5>
            <a href="{{ route('user.publications.create') }}" class="btn btn-primary mt-3">Publier une nouvelle annonce</a>
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
