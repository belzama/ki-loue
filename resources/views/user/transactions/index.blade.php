@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-arrow-left-right me-2"></i> Mes transactions</h4>
</div>

{{-- 🔍 FILTRE (optionnel si tu veux filtrer plus tard) --}}
{{-- <div class="mb-4">
    ...
</div> --}}

{{-- 📋 TABLE DES TRANSACTIONS --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Type</th>
                    <th>Catégorie</th>
                    <th>Référence</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->user->nom ?? '-' }}</td>
                        <td>{{ $transaction->montant }}</td>
                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->categorie }}</td>
                        <td>{{ $transaction->reference }}</td>
                        <td>{{ $transaction->statut }}</td>
                        <td class="text-end">

                            <form action="{{-- route('user.publications.cancel', $transaction) --}}" method="POST" style="display:inline-block;" onsubmit="return confirm('Annuler cette publication ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
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