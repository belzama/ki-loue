@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-journal-text me-2"></i> Mes publications</h4>
    <a href="{{ route('user.publications.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nouvelle publication
    </a>
</div>

{{-- 🔍 FILTRE (optionnel si tu veux filtrer plus tard) --}}
{{-- <div class="mb-4">
    ...
</div> --}}

{{-- 📋 TABLE DES PUBLICATIONS --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Dispositif</th>
                    <th>Ville</th>
                    <th>Tarif</th>
                    <th>Cout de publication</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($publications as $publication)
                    <tr>
                        <td>{{ $publication->id }}</td>
                        <td>{{ $publication->dispositif->type_dispositif->nom ?? '-' }}</td>
                        <td>{{ $publication->dispositif->designation ?? '-' }}</td>
                        <td>{{ $publication->ville->nom ?? '-' }}</td>
                        <td>
                            {{ $publication->tarif_location ?? '-' }}
                            {{ $publication->devise->symbol ?? '' }}
                        </td>
                        <td>
                            {{ $publication->cout_publication }}
                            {{ $publication->devise->symbol ?? '' }}
                        </td>
                        <td>{{ $publication->date_debut }}</td>
                        <td>{{ $publication->date_fin }}</td>
                        <td>
                            <span class="badge {{ $publication->active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $publication->active ? 'Active' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('user.publications.edit', $publication) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('user.publications.destroy', $publication) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cette publication ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Aucune publication trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $publications->withQueryString()->links() }}
</div>

@endsection