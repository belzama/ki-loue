
@extends('layouts.app')

@section('nav-bar')
    @include('partials.welcome-navbar')
@endsection

@section('main-content')
{{-- HERO --}}
<section class="bg-warning text-white py-5">
    <div class="container text-center">
        <p class="lead mt-3">
            Trouvez et louez rapidement des dispositifs disponibles partout en Afrique
        </p>
    </div>
</section>
@include('partials.search-publications-actives')


@endsection
