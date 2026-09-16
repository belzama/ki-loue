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
<div>
    {{-- HERO --}}
    <section class="hero-section">
        <img src="{{ asset('images/hero-engins.jpg') }}" alt="Engins de chantier" class="hero-photo">
        <div class="container hero-content">
            <h1 class="hero-title">
                Location de<br>
                <span class="text-orange">Matériels et</span><br>
                <span class="text-orange">Équipements</span>
            </h1>
        </div>
    </section>

    {{-- CTA : uniques sur toute la page, ne pas les répéter plus bas --}}
    <div class="hero-cta-row">
        <a href="{{ route('catalogue.index') }}" class="btn btn-hero-cta">Accédez à notre catalogue</a>
        <a href="{{ route('catalogue.index') }}" class="btn btn-hero-cta">Tout voir</a>
    </div>

    {{-- CATEGORIES --}}
    <div class="container" style="margin-top: -40px;>
        <section class="section">
            <div class="cats-wrapper">
                <div class="cats-header">
                    <div>
                        <div class="section-title">Nos <span>Catégories</span></div>
                        <div class="section-sub">Matériels disponibles en location</div>
                    </div>
                    {{-- Pas de lien "Tout voir" ici : déjà présent au-dessus du hero --}}
                </div>

                <div class="cats-grid" id="categoriesGrid">
                    @foreach($categories as $categorie)
                        <a href="{{ route('catalogue.index', ['categorie_id' => $categorie->id]) }}"
                        class="cat-card">
                            <div class="cat-icon-box">
                                <img src="{{ asset('storage/'.$categorie->image_link) }}" alt="{{ $categorie->nom }}">
                            </div>
                            <div class="cat-label-box">
                                <span class="cat-name">{{ $categorie->nom }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pas de bouton "Accédez à notre catalogue" ici : déjà présent au-dessus --}}
            </div>
        </section>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <section class="catalogue-band">
        <div class="container text-center">
            <h2 class="catalogue-title">Notre Catalogue</h2>
            <p class="catalogue-sub">({{ $publications->total() }}) matériels disponibles en location</p>
        </div>
    </section>
    
    <div class="container" style="margin-top: -120px">
        @include('partials.search-publications-actives')
    </div>
</div>
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