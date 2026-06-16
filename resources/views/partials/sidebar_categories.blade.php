<aside class="catalogue-sidebar">
    <h3 class="sidebar-title">Catégories</h3>

    <ul class="category-list">
        {{-- "Toutes" comme option de reset --}}
        <li class="category-item {{ !request('categorie_id') ? 'active' : '' }}">
            <a href="{{ route('catalogue.index', request()->except('categorie_id', 'page')) }}">
                Toutes les catégories
            </a>
        </li>

        @foreach ($categories as $categorie)
            <li class="category-item {{ request('categorie_id') == $categorie->id ? 'active' : '' }}">
                <a href="{{ route('catalogue.index', array_merge(request()->except('page'), ['categorie_id' => $categorie->id])) }}">
                    {{ $categorie->nom }}
                </a>
            </li>
        @endforeach
    </ul>
</aside>