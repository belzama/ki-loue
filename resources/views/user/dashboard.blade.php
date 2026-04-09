@extends('layouts.guest')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-grid-1x2-fill text-primary me-2"></i>Tableau de bord
            </h2>
            <p class="text-muted mb-0">Bienvenue, {{ auth()->user()->name }}. Voici l'état de votre parc et vos finances.</p>
        </div>
        <a href="{{ route('user.dispositifs.create') }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Ajouter un matériel
        </a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary p-2 rounded-3">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-subtitle text-muted fw-semibold">Total matériels</h6>
                            <h3 class="fw-bold mb-0">{{ $totalMateriels }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info-subtle text-info p-2 rounded-3">
                            <i class="bi bi-megaphone fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-subtitle text-muted fw-semibold">Matériels en ligne</h6>
                            <h3 class="fw-bold mb-0">{{ $totalEnLigne }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger p-2 rounded-3">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="card-subtitle text-muted fw-semibold">Matériels hors ligne</h6>
                            <h3 class="fw-bold mb-0 text-success">{{ $totalMateriels - $totalEnLigne }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2"></i>Mon Portefeuille</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="bg-light p-3 rounded-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Solde réel</span>
                            <span class="fw-bold">
                                {{ number_format($user->solde_reel, 0, ' ', ' ') }}
                                {{ $user->pays->devise->symbol ?? 'FCFA' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Bonus</span>
                            <span class="fw-bold">
                                + {{ number_format($user->solde_bonus, 0, ' ', ' ') }}
                                {{ $user->pays->devise->symbol ?? 'FCFA' }}
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h6 fw-bold mb-0">Total disponible</span>
                            <span class="fw-bold">
                                {{ number_format($user->solde_reel + $user->solde_bonus, 0, ' ', ' ') }}
                                {{ $user->pays->devise->symbol ?? 'FCFA' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-arrow-left-right me-2"></i>Flux du mois</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        @forelse($statsTransactMoisEncours as $categorie => $montant)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light p-2 me-3">
                                        <i class="bi bi-tag text-secondary"></i>
                                    </div>
                                    <span class="text-capitalize fw-medium">{{ $categorie }}</span>
                                </div>
                                <span class="fw-bold">{{ number_format($montant, 0, ',', ' ') }} CFA</span>
                            </li>
                        @empty
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-slash-circle mb-2 d-block fs-3"></i>
                                Aucun mouvement ce mois.
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Matériels les plus sollicités</h5>
                    <span class="badge bg-primary-subtle text-primary">Top 5</span>
                </div>
                <div class="table-responsive p-3 pt-0">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th class="text-center">Réservations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topDispositifs as $d)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-2 me-3">
                                            <i class="bi bi-box-seam text-primary"></i>
                                        </div>
                                        <span class="text-dark fw-medium">{{ Str::limit($d->designation ?? $d->description, 40) }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-primary px-3">
                                        {{ $d->reservations_count }} / {{ $d->total_jours_publication }} jours
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Optionnel : ajout de quelques classes de fond subtiles si non présentes dans votre CSS */
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1) !important; }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
</style>
@endsection
