
@extends('layouts.app')

@section('nav-bar')
    @guest        
        @include('partials.welcome-navbar')
    @endguest

    @auth
        @include('partials.user-connected-navbar')
    @endauth
@endsection

@section('main-content')
{{-- HERO --}}
<section class="section bg-img text-white">
    <div class="hero-layout">

        {{-- Gauche : texte --}}
        <div class="hero-block">
            <span class="hero-eyebrow">Location de matériel BTP</span>
            <h1 class="hero-title mt-3">
                Le bon matériel,<br>au bon moment.
            </h1>
            <p class="hero-sub mt-3">
                Trouvez et louez rapidement des matériels à volonté.
            </p>
            <a href="{{ route('catalogue.index') }}" class="hero-cta mt-4">
                Accédez à notre catalogue
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>

        {{-- Droite : catégories --}}
        <div class="cats-wrapper">
            <div class="cats-header">
                <div>
                    <div class="section-title">Quelques <span>Catégories</span></div>
                    <div class="section-sub">Matériels disponibles en location</div>
                </div>
                <a href="{{ route('catalogue.index') }}" class="see-all">
                    Tout voir <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="cats-grid" id="categoriesGrid">
                @foreach($categories as $index => $categorie)
                    <a href="{{ route('catalogue.index', ['categorie_id' => $categorie->id]) }}"
                    class="cat-card {{ $index >= 12 ? 'extra-category d-none' : '' }}">
                        <div class="cat-img-wrap">
                            <img src="{{ asset('storage/'.$categorie->image_link) }}"
                                alt="{{ $categorie->nom }}">
                        </div>
                        <div class="cat-name">{{ $categorie->nom }}</div>
                    </a>
                @endforeach
            </div>

            @if($categories->count() > 12)
                <div class="text-center mt-4">
                    <button id="showMoreCategories" class="btn btn-outline-primary">
                        Voir plus de catégories
                    </button>
                </div>
            @endif
        </div>

    </div>
</section>


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@include('partials.search-publications-actives')

@endsection

@push('scripts')
@if(session('open_whatsapp'))
    <a href="{{ session('open_whatsapp') }}"
       id="autoWhatsappLink"
       target="_blank"
       style="display:none;"></a>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("autoWhatsappLink").click();
        });
    </script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('showMoreCategories');

    if (btn) {
        btn.addEventListener('click', function () {

            document.querySelectorAll('.extra-category')
                .forEach(item => item.classList.remove('d-none'));

            btn.style.display = 'none';
        });
    }

});
</script>
@endpush