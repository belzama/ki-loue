<div class="container my-5">

    {{-- FILTRES --}}
    <div class="card mb-4 shadow-sm">
        <form method="GET" class="card shadow-sm p-4">
            <div class="row g-4 align-items-end">

                <!-- Champs à gauche -->
                <div class="col-lg-10">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Continent/Sous région</label>
                            <select id="continent_id" name="continent_id" class="form-select">
                                <option value="">Tous</option>
                                @foreach($continents as $continent)
                                    <option value="{{ $continent->id }}" {{ request('continent_id') == $continent->id ? 'selected' : '' }}>
                                        {{ $continent->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Pays/Région</label>
                            <select id="pays_id" name="pays_id" class="form-select">
                                <option value="">Tous</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Ville/Préfecture</label>
                            <select id="ville_id" name="ville_id" class="form-select">
                                <option value="">Toutes</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Type dispositif</label>
                            <select name="types_dispositif_id" class="form-select">
                                <option value="">Tous</option>
                                @foreach($typesDispositifs as $type)
                                    <option value="{{ $type->id }}" {{ request('types_dispositif_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tarif min</label>
                            <input type="number" name="tarif_min" class="form-control" value="{{ request('tarif_min') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tarif max</label>
                            <input type="number" name="tarif_max" class="form-control" value="{{ request('tarif_max') }}">
                        </div>

                    </div>
                </div>

                <!-- Boutons à droite -->
                <div class="col-lg-2">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark">
                            Rechercher
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            Réinitialiser
                        </button>
                        <a href="{{-- route('filtres.avances') --}}" class="btn btn-outline-primary">
                            Plus de filtres
                        </a>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- LISTE --}}
    <div class="row g-4">
        @forelse($publications as $publication)
            <div class="col-md-4">
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
                        <h5>{{ $publication->dispositif->designation }}</h5>

                        <p class="mb-1">
                            <strong>Type :</strong>
                            {{ $publication->dispositif->type_dispositif->nom }}
                        </p>

                        <p class="mb-1">
                            <strong>Lieu :</strong>
                            {{ $publication->ville->nom }},
                            {{ $publication->ville->pays->nom }}
                        </p>

                        <p class="fw-bold text-success">
                            {{ number_format($publication->tarif_location, 0, ',', ' ') }}
                            {{ $publication->devise->symbol }}
                        </p>

                        {{-- Actions --}}
                        <div class="d-grid gap-2">
                            <a href="{{ route('publications.show', $publication) }}"
                            class="btn btn-outline-primary">
                                Voir détails
                            </a>

                            <a href="{{ route('reservations.create', $publication) }}"
                            class="btn btn-success">
                                Demander réservation
                            </a>
                        </div>
                    </div>
                </div>
            </div>

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

{{-- JS filtres --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const continentSelect = document.getElementById('continent_id');
    const paysSelect = document.getElementById('pays_id');
    const villeSelect = document.getElementById('ville_id');

    // Récupérer les IDs depuis l'URL (si présents)
    const currentPaysId = "{{ request('pays_id') }}";
    const currentVilleId = "{{ request('ville_id') }}";

    function loadPays(continentId, selectedPaysId = null) {
        if (!continentId) return;
        return fetch(`/pays/by-continent/${continentId}`)
            .then(r => r.json())
            .then(data => {
                paysSelect.innerHTML = '<option value="">Tous</option>';
                data.forEach(p => {
                    const selected = p.id == selectedPaysId ? 'selected' : '';
                    paysSelect.innerHTML += `<option value="${p.id}" ${selected}>${p.nom}</option>`;
                });
                if(selectedPaysId) loadVilles(selectedPaysId, currentVilleId);
            });
    }

    function loadVilles(paysId, selectedVilleId = null) {
        if (!paysId) return;
        fetch(`/villes/by-pays/${paysId}`)
            .then(r => r.json())
            .then(data => {
                villeSelect.innerHTML = '<option value="">Toutes</option>';
                data.forEach(v => {
                    const selected = v.id == selectedVilleId ? 'selected' : '';
                    villeSelect.innerHTML += `<option value="${v.id}" ${selected}>${v.nom}</option>`;
                });
            });
    }

    // Au changement manuel
    continentSelect.addEventListener('change', (e) => loadPays(e.target.value));
    paysSelect.addEventListener('change', (e) => loadVilles(e.target.value));

    // Au chargement initial de la page si des filtres existent
    if (continentSelect.value) {
        loadPays(continentSelect.value, currentPaysId);
    }
});
</script>