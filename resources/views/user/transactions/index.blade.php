@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-arrow-left-right me-2"></i> Mes transactions</h4>
</div>

{{-- 🔍 FILTRE PAR DATE --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('user.transactions.index') }}">
            <div class="row align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Date début</label>
                    <input type="date"
                           name="date_debut"
                           class="form-control"
                           value="{{ $dateDebut }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date fin</label>
                    <input type="date"
                           name="date_fin"
                           class="form-control"
                           value="{{ $dateFin }}">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Rechercher
                    </button>

                    <a href="{{ route('user.transactions.index') }}"
                       class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Réinitialiser
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- 📋 TABLE DES TRANSACTIONS --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Type</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->user->nom ?? '-' }}</td>
                        <td>{{ number_format($transaction->montant, 0, '.', ' ') }} {{ $transaction->user->pays->devise->symbol }}</td>
                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->categorie }}</td>
                        <td>{{ $transaction->statut }}</td>
                        <td class="text-end">
                            <a href="{{ route('user.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Voir plus">
                                <i class="bi bi-eye"></i>
                            </a>

                            <!--form action="{{-- route('user.publications.cancel', $transaction) --}}" method="POST" style="display:inline-block;" onsubmit="return confirm('Annuler cette publication ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form-->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Aucune transaction trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $transactions->withQueryString()->links() }}
</div>

@endsection