{{-- FILTRES --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pays</label>
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

                <div class="col-md-3">
                    <label id="label_division" class="form-label fw-semibold">{{ $country?->libelle_division ?? 'Région' }}</label>
                    <select id="region_id" name="region_id"
                            data-child="departement_id"
                            data-url="{{ url('departements/by-region') }}/"
                            data-selected="{{ request('region_id') }}"
                            class="form-select">
                        <option value="">Toutes</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label id="label_sous_division" class="form-label fw-semibold">{{ $country?->libelle_sous_division ?? 'Préfecture' }}</label>
                    <select id="departement_id" name="departement_id"
                            data-selected="{{ request('departement_id') }}"
                            class="form-select">
                        <option value="">Toutes</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-grow-1" title="Rechercher">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary flex-grow-1" title="Réinitialiser">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>

            </div>

        </form>
    </div>
</div>