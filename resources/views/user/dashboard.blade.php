@extends('layouts.guest')

@section('content')

<div class="container mb-4">
    <div class="row align-items-center bg-white shadow-sm rounded-3 p-4">

        <!-- Titre -->
        <div class="col-md-6 mb-3 mb-md-0">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 text-primary me-2"></i>
                Tableau de bord
            </h2>
        </div>

        <!-- Bouton -->
        <div class="col-md-6 text-md-end">
            <a href="{{ route('user.dispositifs.create') }}"
               class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>
                Ajouter un matériel
            </a>
        </div>

    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6>Total publications</h6>
                <h3>{{ $totalPublications }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h6>Publications actives</h6>
                <h3>{{ $activePublications }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-danger">
            <div class="card-body">
                <h6>Publications expirées</h6>
                <h3>{{ $expiredPublications }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h6>Publications ce mois</h6>
                <h3>{{ $monthlyPublications }}</h3>
            </div>
        </div>
    </div>
</div>

<hr>

<h4>💰 Revenus</h4>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <strong>Total :</strong> {{ number_format($totalRevenue,0,' ',' ') }}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <strong>Ce mois :</strong> {{ number_format($monthlyRevenue,0,' ',' ') }}
            </div>
        </div>
    </div>
</div>

<h5>Revenus par devise</h5>
<table class="table">
    <tr>
        <th>Devise</th>
        <th>Total</th>
    </tr>
    @foreach($revenueByCurrency as $r)
    <tr>
        <td>{{ $r->devise->code }}</td>
        <td>{{ number_format($r->total,0,' ',' ') }}</td>
    </tr>
    @endforeach
</table>

<hr>

<h5>🏗 Top matériels</h5>
<table class="table">
    <tr>
        <th>Description</th>
        <th>Publications</th>
    </tr>
    @foreach($topDispositifs as $d)
    <tr>
        <td>{{ Str::limit($d->description,50) }}</td>
        <td>{{ $d->publications_count }}</td>
    </tr>
    @endforeach
</table>

@endsection
