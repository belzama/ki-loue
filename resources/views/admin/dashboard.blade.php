@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><i class="bi bi-speedometer2 me-2"></i>Tableau de bord</h2>
        <span class="badge bg-light text-dark border p-2">
            <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('d F Y') }}
        </span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Utilisateurs</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                        <i class="bi bi-people text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card border-0 shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Matériels</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0">{{ $totalMateriels }}</h3>
                        <i class="bi bi-truck text-info fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Actifs</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0 text-success">{{ $activePublications }}</h3>
                        <i class="bi bi-check-circle text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Expirés</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0 text-danger">{{ $expiredPublications }}</h3>
                        <i class="bi bi-clock-history text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Ce mois</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0">{{ $monthlyPublications }}</h3>
                        <i class="bi bi-calendar-check text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">💰 Analyse des revenus</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-6 border-end">
                            <p class="text-muted mb-1">Chiffre d'Affaires Global</p>
                            <h2 class="fw-bold text-primary">{{ number_format($totalRevenue, 0, ' ', ' ') }} <small class="fs-6">CFA</small></h2>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Chiffre d'Affaires Mensuel</p>
                            <h2 class="fw-bold text-success">{{ number_format($monthlyRevenue, 0, ' ', ' ') }} <small class="fs-6">CFA</small></h2>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Répartition par devise</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Devise</th>
                                    <th class="text-end">Montant total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueByCurrency as $r)
                                <tr>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $r->devise->code }}</span></td>
                                    <td class="text-end fw-bold">{{ number_format($r->total, 0, ' ', ' ') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">💳 Flux par catégorie (Mois)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($statsTransactMoisEncours as $cat => $val)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-capitalize text-muted"><i class="bi bi-dot text-primary fs-4"></i>{{ $cat }}</span>
                            <span class="fw-bold">{{ number_format($val, 0, ' ', ' ') }} FCFA</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted">Aucune transaction</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">🏆 Utilisateurs les plus rentables</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <tbody>
                            @foreach($topUsers as $u)
                            <tr>
                                <td class="ps-3">{{ $u->name }}</td>
                                <td class="text-end pe-3 fw-bold text-success">{{ number_format($u->CA_total, 0, ' ', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">📉 Utilisateurs les moins rentables</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <tbody>
                            @foreach($flopUsers as $u)
                            <tr>
                                <td class="ps-3">{{ $u->name }}</td>
                                <td class="text-end pe-3 fw-bold text-danger">{{ number_format($u->CA_total ?? 0, 0, ' ', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title fw-bold mb-0">🏗 Matériels les plus sollicités</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Matériel</th>
                        <th class="text-center">Total Réservations</th>
                        <th class="text-center">Total Publications</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topDispositifs as $d)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-medium">{{ $d->designation }}</span><br>
                            <small class="text-muted">{{ Str::limit($d->description, 60) }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill">{{ $d->reservations_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill">{{ $d->publications_count }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
    .bg-secondary-subtle { background-color: #e9ecef; }
</style>
@endsection