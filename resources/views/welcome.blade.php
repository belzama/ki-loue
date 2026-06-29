
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
    <div class="hero-layout"><!-- 

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
        </div> -->

        {{-- Droite : catégories --}}
        <div class="cats-wrapper">
            <div class="cats-header">
                <div>
                    <div class="section-title">Nos <span>Catégories</span></div>
                    <div class="section-sub">Matériels disponibles en location</div>
                </div>
                <a href="{{ route('catalogue.index') }}" class="see-all">
                    Tout voir <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="cats-grid" id="categoriesGrid">
                @foreach($categories as $categorie)
                    <a href="{{ route('catalogue.index', ['categorie_id' => $categorie->id]) }}"
                    class="cat-card">
                        <div class="cat-img-wrap">
                            <img src="{{ asset('storage/'.$categorie->image_link) }}"
                                alt="{{ $categorie->nom }}">
                        </div>
                        <div class="cat-name">{{ $categorie->nom }}</div>
                    </a>
                @endforeach
            </div>
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
@endpush