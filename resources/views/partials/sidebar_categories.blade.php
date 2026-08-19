<aside class="catalogue-sidebar">
    <h3 class="sidebar-title">Catégories</h3>

    {{-- Champ de filtre --}}
    <div class="category-search">
        <input type="text"
               id="category-filter"
               placeholder="Rechercher une catégorie..."
               class="category-search-input">
    </div>

    <ul class="category-list" id="category-list">
        @php $activeCat = (int) request('categorie_id'); @endphp

        <li class="category-item {{ !$activeCat ? 'active' : '' }}" data-name="toutes les catégories">
            <a href="{{ route('catalogue.index', request()->except('categorie_id', 'page')) }}"
               {{ !$activeCat ? 'aria-current="page"' : '' }}>
                <span class="category-img category-img--placeholder">🎛️</span>
                <span class="category-name">Toutes les catégories</span>
                <!-- <span class="category-count">{{ $categories->sum('publications_actives_count') }}</span> -->
                <span class="category-count">{{ $publications->total() }}</span>
            </a>
        </li>

        @foreach ($categories as $categorie)
            <li class="category-item {{ $activeCat === $categorie->id ? 'active' : '' }}"
                data-name="{{ strtolower($categorie->nom) }}">
                <a href="{{ route('catalogue.index', array_merge(request()->except('page'), ['categorie_id' => $categorie->id])) }}"
                   {{ $activeCat === $categorie->id ? 'aria-current="page"' : '' }}>

                    @if ($categorie->image_link)
                        <img src="{{ asset('storage/'.$categorie->image_link) }}"
                             alt="{{ $categorie->nom }}"
                             class="category-img">
                    @else
                        <span class="category-img category-img--placeholder">🗂️</span>
                    @endif

                    <span class="category-name">{{ $categorie->nom }}</span>
                    <span class="category-count">{{ $categorie->publications_actives_count ?? 0 }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</aside>

@push('scripts')
<script>
    document.getElementById('category-filter').addEventListener('input', function () {
        const search = this.value.toLowerCase().trim();
        const items  = document.querySelectorAll('#category-list .category-item');

        items.forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(search) ? '' : 'none';
        });
    });
</script>
@endpush

