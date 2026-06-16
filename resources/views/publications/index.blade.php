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
            @include('partials.localisation_search_form')
            
            @include('partials.search-publications-actives')
        </main>

    </div>

@endsection