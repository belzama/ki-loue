@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('title', 'Nouvel abonnement')

@section('content')
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="container py-4">

    {{-- En-tête --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-bell me-2 text-success"></i>Choisir un abonnement
            </h4>
            @isset($dispositif)
                <small class="text-muted">
                    Dispositif : <strong>{{ $dispositif->designation }}</strong>
                </small>
            @endisset
        </div>
        <a href="{{ route('user.dispositifs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
    </div>

    {{-- Erreurs --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Cartes périodicités --}}
    <div class="row g-4">
        @foreach($periodicites as $periodicite)

            @php
                $debut    = now()->startOfDay();
                $fin      = $debut->copy()->addDays($periodicite->nb_jour);
                $moy      = ($dispositif->tarif_min + $dispositif->tarif_max) / 2;
                $taux     = $dispositif->user->taux_tarif_abonnement / 100;
                $montant  = $moy * $taux * $periodicite->qte;
                $remise   = $montant * ($periodicite->taux_remise / 100);
                $total    = $montant - $remise;
            @endphp

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">

                    {{-- Header carte --}}
                    <div class="card-header bg-{{ $periodicite->couleur }} text-white text-center py-3 border-0">
                        <h5 class="mb-0 fw-bold">{{ $periodicite->libelle }}</h5>
                        <small class="opacity-75">
                            {{ $periodicite->nb_jour }} jours
                            {{ $periodicite->qte }} unite
                        </small>
                    </div>

                    <div class="card-body d-flex flex-column gap-3 p-4">

                        {{-- Dates --}}
                        <div class="text-center fw-bold">
                            <small class="opacity-100">
                                {{ $debut->format('d/m/Y') }} - {{ $fin->format('d/m/Y') }}
                            </small>
                        </div>

                        {{-- Montant --}}
                        <div class="text-center py-2">
                            @if($periodicite->taux_remise > 0)
                                <div class="text-muted text-decoration-line-through small">
                                    {{ number_format($montant, 0, ',', ' ') }} FCFA
                                </div>
                                <div class="badge bg-danger mb-1">
                                    -{{ $periodicite->taux_remise }}% de remise
                                </div>
                            @endif
                            <div class="fs-3 fw-bold text-success">
                                {{ number_format($total, 0, ',', ' ') }}
                                <span class="fs-6 text-muted fw-normal">FCFA</span>
                            </div>
                            <small class="text-muted">
                                soit {{ number_format($total / ($periodicite->qte), 0, ',', ' ') }} FCFA / unité
                            </small>
                        </div>

                        {{-- Bouton Payer --}}
                        <form action="{{ route('user.abonnements.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="dispositif_id"   value="{{ $dispositif->id }}">
                            <input type="hidden" name="periodicite_id"  value="{{ $periodicite->id }}">
                            <input type="hidden" name="date_debut"      value="{{ $debut->toDateString() }}">
                            <input type="hidden" name="date_fin"        value="{{ $fin->toDateString() }}">
                            <input type="hidden" name="montant"         value="{{ number_format($total, 2, '.', '') }}">
                            <input type="hidden" name="actif"           value="1">

                            <button type="submit"
                                    class="btn btn-{{ $periodicite->couleur }} w-100 py-2 fw-semibold">
                                <i class="bi bi-credit-card me-2"></i>Payer
                            </button>
                        </form>

                    </div>
                </div>
            </div>

        @endforeach
    </div>

</div>
@endsection