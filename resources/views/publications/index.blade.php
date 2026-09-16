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

    <div class="catalogue-layout">

        {{-- Sidebar gauche : catégories --}}
        @include('partials.sidebar_categories')

        {{-- Contenu principal --}}
        <main class="catalogue-main">
            <div class="catalogue-header">                    
                @include('partials.localisation_search_form')
            </div>
            
            <div class="catalogue-body">
                <div class="text-center">
                <h2 class="catalogue-title">Notre Catalogue</h2>
                    <p class="catalogue-sub">({{ $publications->total() }}) matériels disponibles en location</p>
                </div>
                <div style="margin: 10px;">                
                    @include('partials.search-publications-actives')
                </div>
            </div>
        </main>
    </div>

@endsection