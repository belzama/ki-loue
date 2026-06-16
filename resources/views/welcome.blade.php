
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
<section class="section bg-img text-white py-5">
    <div class="text-center">
        <p class="lead mt-3">
            Trouvez et louez rapidement des matériels disponibles partout en Afrique
        </p>
    </div>

    {{-- CATÉGORIES DE MATÉRIEL --}}
    <div class="cats-container">
        <div class="section-header">
            <div>
                <div class="section-title">Nos Catégories</div>
                <div class="section-sub">Matériels disponibles en location</div>
            </div>
            <a href="{{ route('catalogue.index') }}" class="see-all">Toutes les catégories →</a>
        </div>

        <div class="cats-grid">
            @foreach($categories as $categorie)
                <a href="{{ route('catalogue.index', ['categorie_id' => $categorie->id]) }}" class="cat-card">
                    <span class="cat-icon">
                        <img src="{{ asset('storage/'.$categorie->image_link) }}" alt="{{ $categorie->nom }}" height="80">
                    </span>
                    <div>
                        <div class="cat-name">{{ $categorie->nom }}</div>
                        <div class="cat-count">{{ $categorie->dispositifs_count ?? 0 }} matériel(s)</div>
                    </div>
                </a>
            @endforeach
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