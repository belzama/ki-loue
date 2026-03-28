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

<div class="container my-4">

    {{-- ===== Titre ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-4" 
        style="margin-top: 3.5em;">
        <h3 class="fw-bold">
            {{ $publication->dispositif->designation }}
        </h3>

        {{-- ===== CONTACT ===== --}}

        <div class="d-flex flex-wrap gap-2">

            <form action="{{ route('reservations.store', $publication->id) }}" method="POST" id="contactForm">
                @csrf

                {{-- Afficher le numéro (PC) --}}
                @if($publication->dispositif->user->telephone)
                    <button type="button"
                            class="btn btn-primary d-none d-lg-inline"
                            id="showPhoneBtn"
                            data-action="show_number">
                        <i class="bi bi-telephone-fill"></i> Afficher le N° téléphone
                    </button>

                    {{-- Appeler (mobile) --}}
                    <button type="button"
                            class="btn btn-primary d-lg-none"
                            id="callBtn"
                            data-action="call">
                        <i class="bi bi-telephone-fill"></i> Appeler
                    </button>

                    {{-- SMS (mobile) --}}
                    <button type="button"
                            class="btn btn-secondary d-lg-none"
                            id="smsBtn"
                            data-action="sms">
                        <i class="bi bi-chat-dots-fill"></i> Envoyer un SMS
                    </button>
                @endif

                {{-- WhatsApp --}}
                @if($publication->dispositif->user->whatsapp)
                    <button type="button"
                            class="btn btn-success"
                            id="whatsappBtn"
                            data-action="whatsapp">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </button>
                @endif

                {{-- Email --}}
                @if($publication->dispositif->user->email)
                    <button type="button"
                            class="btn btn-warning"
                            id="emailBtn"
                            data-action="email">
                        <i class="bi bi-envelope-fill"></i> Envoyer Email
                    </button>
                @endif
            </form>

        </div>
    </div>


    <div class="row g-3">

        {{-- ===== INFOS DISPOSITIF ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-dark text-white fw-semibold">
                    Matériel
                </div>

                <div class="card-body small">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>Catégorie :</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $publication->dispositif->type_dispositif->categorie->nom }}
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>Matériel :</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $publication->dispositif->type_dispositif->nom }}
                        </div>
                    </div>

                    @if($publication->dispositif->marque)
                        <div class="row g-2">
                            <div class="col-md-4">
                                <strong>Marque :</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $publication->dispositif->marque ?? '-' }}
                            </div>
                        </div>
                    @endif

                    @if($publication->dispositif->modele)
                        <div class="row g-2">
                            <div class="col-md-4">
                                <strong>Modèle :</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $publication->dispositif->modele ?? '-' }}
                            </div>
                        </div>
                    @endif

                    {{-- paramètres dynamiques --}}
                    @foreach($publication->dispositif->params as $param)
                        <div class="row g-2">
                            <div class="col-md-4">
                                <strong>{{ $param->typeParam->label ?? ucfirst($param->name) }} :</strong>
                            </div>                            
                            <div class="col-md-8">
                                {{ $param->value }}
                            </div>
                        </div>
                    @endforeach

                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>État :</strong>
                        </div>
                        <div class="col-md-4">
                            <span class="badge bg-info">
                                {{ $publication->dispositif->etat }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== LOCALISATION ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-dark text-white fw-semibold">
                    Localisation
                </div>

                <div class="card-body small">

                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>Pays :</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $publication->departement->region->pays->nom }}
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>{{ $publication->departement->region->pays->libelle_division }} :</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $publication->departement->region->nom }}
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>{{ $publication->departement->region->pays->libelle_sous_division }} :</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $publication->departement->nom }}
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <strong>Ville / Localité :</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $publication->ville }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===== TARIF ===== --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0 text-center">

                <div class="card-header bg-success text-white fw-semibold">
                    Tarif de location
                </div>

                <div class="card-body d-flex align-items-center justify-content-center">

                    <div class="display-6 fw-bold text-success">
                        {{ number_format($publication->tarif_location,0,' ',' ') }}
                        <small class="fs-6">FCFA / jour</small>
                    </div>

                </div>

            </div>
        </div>

    </div>


    {{-- ===== GALERIE ===== --}}
    <div class="card mt-4 shadow-sm border-0">

        <div class="card-body">

            <div class="lightbox-gallery row g-3">

                @forelse($publication->dispositif->photos as $index => $photo)

                    <div class="col-md-3 col-6">

                        <img
                            src="{{ asset('storage/'.$photo->path) }}"
                            class="img-fluid rounded shadow-sm lightbox-item"
                            style="height:170px; object-fit:cover; cursor:pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#photoModal"
                            data-index="{{ $index }}"
                        >

                    </div>

                @empty

                    <div class="col-12 text-center text-muted">
                        Aucune photo disponible
                    </div>

                @endforelse

            </div>

        </div>
    </div>

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            ← Retour
        </a>
    </div>

</div>

@include('partials.photo-viewer')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){

    const form = document.getElementById('contactForm');

    const phone = '{{ $publication->dispositif->user->telephone }}';
    const whatsapp = '{{ $publication->dispositif->user->whatsapp ?? "" }}';
    const email = '{{ $publication->dispositif->user->email ?? "" }}';

    const publicationLink = '{{ route("publications.show", $publication->id) }}';

    const message =
`Bonjour,

Je souhaite réserver votre matériel {{ $publication->dispositif->designation }}.

Voir la publication :
${publicationLink}`;

    function sendReservation(action, callback) {
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action })
        })
        .then(res => res.json())
        .then(data => {
            if(callback) callback(data);
        })
        .catch(err => console.error(err));
    }

    // ===== Afficher numéro =====
    document.getElementById('showPhoneBtn')?.addEventListener('click', function(){
        sendReservation('show_number', () => {
            this.innerHTML = '<i class="bi bi-telephone-fill"></i> ' + phone;
        });
    });

    // ===== Appeler =====
    document.getElementById('callBtn')?.addEventListener('click', function(){
        sendReservation('call', () => {
            window.location.href = 'tel:' + phone;
        });
    });

    // ===== SMS =====
    document.getElementById('smsBtn')?.addEventListener('click', function(){

        const smsMessage = encodeURIComponent(message);

        sendReservation('sms', () => {
            window.location.href = `sms:${phone}?body=${smsMessage}`;
        });

    });

    // ===== WhatsApp =====
    document.getElementById('whatsappBtn')?.addEventListener('click', function(){

        const whatsappMessage = encodeURIComponent(message);

        sendReservation('whatsapp', () => {
            window.open(`https://wa.me/${whatsapp}?text=${whatsappMessage}`, '_blank');
        });

    });

    // ===== Email =====
    document.getElementById('emailBtn')?.addEventListener('click', function(){

        const subject = encodeURIComponent('Demande de réservation');
        const body = encodeURIComponent(message);

        sendReservation('email', () => {
            window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
        });

    });

});
</script>
@endpush