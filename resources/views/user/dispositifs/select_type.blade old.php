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

@foreach($categories as $cat)
    @if($cat->types_dispositifs->isNotEmpty())
    <div class="categorie-section mb-4" data-nom="{{ strtolower($cat->nom) }}">
        <h5 class="mb-2">{{ $cat->nom }}</h5>
        <div class="d-flex flex-row flex-wrap gap-3 overflow-auto pb-2">
            @foreach($cat->types_dispositifs as $type)
                <a href="{{ route('user.dispositifs.create', $type->id) }}"
                   class="type-card text-decoration-none text-dark"
                   data-nom="{{ strtolower($type->nom) }}"
                   style="width: 160px; flex: 0 0 auto;">
                    <div class="card h-100">
                        @if($type->image_link)
                            <img src="{{ asset('storage/' . $type->image_link) }}"
                                 class="card-img-top" style="height: 100px; object-fit: cover;"
                                 alt="{{ $type->nom }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                 style="height: 100px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body p-2 text-center">
                            <div class="small fw-semibold">{{ $type->nom }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
@endforeach

<div id="no-results" class="text-muted d-none">Aucun résultat pour cette recherche.</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    function applyFilter() {
        const search = $('#filter-search').val().trim().toLowerCase();
        let anyVisibleGlobal = false;

        $('.categorie-section').each(function () {
            const $section = $(this);
            const catMatch = !search || $section.data('nom').includes(search);

            if (catMatch) {
                // la catégorie correspond -> tous ses types s'affichent
                $section.removeClass('d-none');
                $section.find('.type-card').removeClass('d-none');
                anyVisibleGlobal = true;
                return;
            }

            // sinon on filtre au niveau des types individuellement
            let anyVisibleInSection = false;
            $section.find('.type-card').each(function () {
                const typeMatch = $(this).data('nom').includes(search);
                $(this).toggleClass('d-none', !typeMatch);
                if (typeMatch) anyVisibleInSection = true;
            });

            $section.toggleClass('d-none', !anyVisibleInSection);
            if (anyVisibleInSection) anyVisibleGlobal = true;
        });

        $('#no-results').toggleClass('d-none', anyVisibleGlobal);
    }

    $('#filter-search').on('input', applyFilter);
});
</script>
@endpush