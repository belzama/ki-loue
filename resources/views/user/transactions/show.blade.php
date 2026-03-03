@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<div class="container my-4">

    {{-- ===== Titre + Statut ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            Transaction — {{ $transaction->id }} {{ $transaction->reference }}
        </h3>

        <span class="badge
            @if($transaction->statut=='effectuee') bg-success
            @elseif($transaction->statut=='en_attente') bg-warning
            @elseif($transaction->statut=='echoue') bg-danger
            @elseif($transaction->statut=='annulee') bg-cancel
            @else bg-danger @endif">
            {{ $transaction->statut }}
        </span>
    </div>


    {{-- ===== 1. INFOS TRANSACTION ===== --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-primary text-white">
            Informations de transaction
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Date de la transaction</strong><br>
                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                </div>

                <div class="col-md-4">
                    <strong>Montant de la transaction</strong><br>
                    {{ number_format($transaction->montant, 0, '.', ' ') }} {{ $transaction->user->pays->devise->symbol }}
                </div>

                <div class="col-md-4">
                    <strong>Solde après transaction</strong><br>
                    {{ number_format($transaction->solde_apres, 0, '.', ' ') }} {{ $transaction->user->pays->devise->symbol }}
                </div>

                <div class="col-md-4">
                    <strong>Type</strong><br>
                    {{ $transaction->type }}
                </div>

                <div class="col-md-4">
                    <strong>Catégorie</strong><br>
                    {{ $transaction->categorie }}
                </div>

                <div class="col-md-4">
                    <strong>Description</strong><br>
                    {{ $transaction->description }}
                </div>
            </div>
        </div>
    </div>


    {{-- ===== 2. INFOS USER ===== --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-secondary text-white">
            Informations Utilisateur
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Nom & prénom</strong><br>
                    {{ $transaction->user->nom }}
                </div>

                <div class="col-md-4">
                    <strong>Téléphone</strong><br>
                    {{ $transaction->user->contact }}
                </div>

                <div class="col-md-4">
                    <strong>Email</strong><br>
                    {{ $transaction->user->email }}
                </div>
            </div>
        </div>
    </div>


    {{-- ===== ACTIONS ===== --}}

</div>


@include('partials.photo-viewer')
@endsection
