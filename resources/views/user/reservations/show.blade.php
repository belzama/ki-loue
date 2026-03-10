@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<div class="container my-4">

    {{-- ===== Titre + Statut ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            Réservation — {{ $reservation->publication->dispositif->designation }}
        </h3>

        <span class="badge
            @if($reservation->statut=='Demandée') bg-warning
            @elseif($reservation->statut=='Accordée') bg-success
            @else bg-danger @endif">
            {{ $reservation->statut }}
        </span>
    </div>


    {{-- ===== 1. INFOS RESERVATION ===== --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-primary text-white">
            Informations de réservation
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Date souhaitée</strong><br>
                    {{ $reservation->date_demandee }}
                </div>

                <div class="col-md-4">
                    <strong>Durée</strong><br>
                    {{ $reservation->duree_demandee }} jours
                </div>

                <div class="col-md-4">
                    <strong>Tarif journalier</strong><br>
                    {{ number_format($reservation->publication->tarif_location, 0, ',', ' ') }}
                    {{ $reservation->publication->devise->symbol }}
                </div>
            </div>
        </div>
    </div>


    {{-- ===== 2. INFOS CLIENT ===== --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-secondary text-white">
            Informations client
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Nom & prénom</strong><br>
                    {{ $reservation->nom_prenom }}
                </div>

                <div class="col-md-4">
                    <strong>Téléphone</strong><br>
                    {{ $reservation->telephone }}
                </div>

                <div class="col-md-4">
                    <strong>Email</strong><br>
                    {{ $reservation->email }}
                </div>
            </div>
        </div>
    </div>


    {{-- ===== 3. INFOS DISPOSITIF ===== --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-dark text-white">
            Informations du matériel
        </div>

        <div class="card-body">
            <p><strong>Type :</strong>
                {{ $reservation->publication->dispositif->type_dispositif->nom }}
            </p>

            <p><strong>Lieu :</strong>
                {{ $reservation->publication->ville->nom }},
                {{ $reservation->publication->ville->region->pays->nom }}
            </p>

            {{-- Paramètres --}}
            @if($reservation->publication->dispositif->params->count())
                <hr>
                <h6 class="mb-3">Caractéristiques</h6>

                <div class="row">
                    @foreach($reservation->publication->dispositif->params as $param)
                        <div class="col-md-3 mb-2">
                            <div class="border rounded p-2 bg-light">
                                <small class="text-muted">
                                    {{ $param->typeParam->label ?? ucfirst($param->name) }}
                                </small>
                                <div class="fw-bold">
                                    {{ $param->value }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


    {{-- ===== 4. GALERIE PHOTOS ===== --}}
    <div class="card mb-3 shadow-sm">

        <div class="card-body">
            <div class="lightbox-gallery row g-2">
                @forelse($reservation->publication->dispositif->photos as $index => $photo)
                    <div class="col-md-3 mb-3">
                        <img src="{{ asset('storage/' . $photo->path) }}"
                        class="img-fluid rounded shadow-sm lightbox-item"
                        style="height:150px; object-fit:cover; cursor:pointer;"
                        data-bs-toggle="modal"
                        data-bs-target="#photoModal"
                        data-index="{{ $index }}">
                    </div>
                @empty
                    <div class="col-12 text-center text-muted">
                        Aucune photo disponible
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ===== ACTIONS ===== --}}
    @if($reservation->statut == 'Demandée')
        <a href="{{ route('user.reservations.approve.form',$reservation->id) }}"
           class="btn btn-success">
            Accepter
        </a>

        <a href="{{ route('user.reservations.reject.form',$reservation->id) }}"
           class="btn btn-danger">
            Rejeter
        </a>
    @endif

</div>


@include('partials.photo-viewer')
@endsection
