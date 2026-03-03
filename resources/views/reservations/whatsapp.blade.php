@extends('layouts.app')

@section('nav-bar')
    @auth
        @include('partials.user-connected-navbar')
    @endauth

    @guest
        @include('partials.welcome-navbar')
    @endguest
@endsection

@section('main-content')
<div class="container text-center mt-5">
 
    <h3 class="mb-4">Votre demande a été enregistrée ✅</h3>

    <p>Vous allez être redirigé vers WhatsApp pour envoyer votre message.</p>

    <a href="https://wa.me/{{ request('telephone') }}?text={{ request('message') }}"
       class="btn btn-success btn-lg"
       id="btnWhatsapp">
        Ouvrir WhatsApp
    </a>

    <div class="mt-3">
        <a href="{{ route('user.reservations.index') }}" class="btn btn-secondary">
            Retour à mes réservations
        </a>
    </div>

</div>

<script>
    // ouverture automatique après 2 secondes
    setTimeout(function () {
        window.location.href =
            "https://wa.me/{{ request('telephone') }}?text={{ request('message') }}";
    }, 2000);
</script>
@endsection
