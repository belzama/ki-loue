
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