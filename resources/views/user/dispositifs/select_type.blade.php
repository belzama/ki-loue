@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-truck me-2"></i> Nouveau matériel — choisir un type</h4>
</div>

<div class="mb-4">
    <input type="text" id="filter-search" class="form-control"
           placeholder="Rechercher une catégorie ou un type...">
</div>

<div class="categories-row d-flex flex-wrap gap-4 mb-4" id="categories-row">
    @foreach($categories as $cat)
        @if($cat->types_dispositifs->isNotEmpty())
        <div class="cat-item text-center"
             data-nom="{{ strtolower($cat->nom) }}"
             data-cat-nom="{{ $cat->nom }}"
             data-count="{{ $cat->types_dispositifs->count() }}"
             role="button">
            <div class="cat-icon-circle mx-auto mb-2 d-flex align-items-center justify-content-center">
                @if($cat->image_link)
                    <img src="{{ asset('storage/' . $cat->image_link) }}" alt="{{ $cat->nom }}">
                @else
                    <i class="bi bi-truck fs-3 text-muted"></i>
                @endif
            </div>
            <div class="small fw-semibold">{{ $cat->nom }}</div>

            {{-- Template caché : liste verticale des types de cette catégorie --}}
            <div class="cat-types-template d-none">
                <div class="list-group">
                    @foreach($cat->types_dispositifs as $type)
                        @php($imgSrc = $type->image_link ?: $cat->image_link)
                        <a href="{{ route('user.dispositifs.create', $type->id) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-3 type-card"
                        data-nom="{{ strtolower($type->nom) }}">
                            @if($imgSrc)
                                <img src="{{ asset('storage/' . $imgSrc) }}"
                                    alt="{{ $type->nom }}"
                                    style="width: 48px; height: 48px; object-fit: cover; border-radius: .375rem;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px; border-radius: .375rem;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            <span class="fw-semibold">{{ $type->nom }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>

<div id="no-results" class="text-muted d-none">Aucun résultat pour cette recherche.</div>

{{-- Modal partagée : son contenu est rempli dynamiquement au clic --}}
<div class="modal fade" id="typesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typesModalLabel">Types</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="typesModalBody"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .cat-icon-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background-color: #e9ecef;
        overflow: hidden;
        border: 2px solid transparent;
        transition: border-color .2s ease, transform .2s ease;
    }

    .cat-icon-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cat-item {
        cursor: pointer;
    }

    .cat-item:hover .cat-icon-circle {
        border-color: #f39200;
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    const typesModalEl = document.getElementById('typesModal');
    const typesModal = new bootstrap.Modal(typesModalEl);

    $('.cat-item').on('click', function () {
        const $item = $(this);
        const count = parseInt($item.data('count'), 10);
        const $links = $item.find('.cat-types-template a.type-card');

        // Un seul type -> redirection directe, pas de modal
        if (count === 1) {
            window.location.href = $links.first().attr('href');
            return;
        }

        // Plusieurs types -> ouverture de la modal avec la liste verticale
        $('#typesModalLabel').text($item.data('cat-nom'));
        $('#typesModalBody').html($item.find('.cat-types-template').html());
        typesModal.show();
    });

    function applyFilter() {
        const search = $('#filter-search').val().trim().toLowerCase();
        let anyVisibleGlobal = false;

        $('.cat-item').each(function () {
            const $item = $(this);
            const catMatch = $item.data('nom').includes(search);
            const typeMatch = $item.find('.type-card').toArray()
                .some(el => $(el).data('nom').includes(search));

            const visible = !search || catMatch || typeMatch;
            $item.toggleClass('d-none', !visible);
            if (visible) anyVisibleGlobal = true;
        });

        $('#no-results').toggleClass('d-none', anyVisibleGlobal);
    }

    $('#filter-search').on('input', applyFilter);
});
</script>
@endpush