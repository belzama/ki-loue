@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>
            <i class="bi bi-receipt me-2"></i> 
            Mes abonnements ({{ $abonnements->total() }})
        </h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Matériel</th>
                <th>Périodicité</th>
                <th>Période</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($abonnements as $abonnement)
                @php
                    $isExpired = $abonnement->date_fin->isPast();
                    $isActive  = $abonnement->actif && !$isExpired;
                @endphp
                <tr>
                    <td>{{ $abonnement->created_at->format('d/m/Y H:i') }}</td>

                    <td>
                        {{ $abonnement->dispositif->designation ?? '' }}
                        @if($abonnement->dispositif->numero_immatriculation)
                            <span class="badge bg-light text-dark border">
                                # {{ $abonnement->dispositif->numero_immatriculation }}
                            </span>
                        @endif
                    </td>

                    <td>{{ $abonnement->periodicite->libelle ?? '' }}</td>

                    <td>
                        {{ $abonnement->date_debut->format('d/m/Y') }} -
                        {{ $abonnement->date_fin->format('d/m/Y') }}
                    </td>

                    <td class="fw-semibold">
                        {{ number_format($abonnement->montant, 0, ',', ' ') }}
                        {{ $abonnement->dispositif->user->pays->devise->symbol ?? 'FCFA' }}
                    </td>

                    <td>
                        @if($isActive)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Actif
                            </span>
                        @elseif($isExpired)
                            <span class="badge bg-secondary">
                                <i class="bi bi-calendar-x me-1"></i>Expiré
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="bi bi-pause-circle me-1"></i>Inactif
                            </span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('user.abonnements.show', $abonnement) }}"
                        class="btn btn-sm btn-outline-primary" title="Voir plus">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Aucun abonnement trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $abonnements->links() }}
</div>

@endsection
