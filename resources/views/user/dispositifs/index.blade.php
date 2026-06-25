@extends(auth()->user()->role == 'Admin' ? 'layouts.admin' : 'layouts.guest')

@section('content')

{{-- PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-truck me-2"></i> Mes matériels ({{ $dispositifs->total() }})</h4>
    <a href="{{ route('user.dispositifs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Ajouter un matériel
    </a>
</div>

{{-- 🔍 FILTRE --}}
<div class="card shadow-sm mb-4 p-3">
    <form method="GET" action="{{ route('user.dispositifs.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Catégorie</label>
                <select id="categorie_id"
                        name="categorie_id"
                        class="form-select">
                    <option value="">-- Toutes les catégories --</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Type de matériel</label>
                <select id="types_dispositif_id"
                        name="types_dispositif_id"
                        data-selected="{{ request('types_dispositif_id') }}"
                        class="form-select">
                    <option value="">-- Tous les types --</option>
                    {{-- Les options seront chargées par AJAX ou via le script --}}
                </select>
            </div>

            <div class="col-md-1">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- 📋 LISTE DES DISPOSITIFS --}}
<div class="row g-4">
    @forelse($dispositifs as $dispositif)
        <div class="col-sm-6 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                {{-- Carousel photos --}}
                <div class="position-relative">
                    <div id="carousel{{ $dispositif->id }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @forelse($dispositif->photos as $index => $photo)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/'.$photo->path) }}"
                                        class="d-block w-100"
                                        style="height:220px; object-fit:cover;"
                                        alt="photo dispositif">
                                </div>
                            @empty
                                <div class="carousel-item active">
                                    <img src="{{ asset('images/no-image.png') }}"
                                        class="d-block w-100"
                                        style="height:220px; object-fit:cover;">
                                </div>
                            @endforelse
                        </div>

                        @if($dispositif->photos->count() > 1)
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carousel{{ $dispositif->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carousel{{ $dispositif->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>

                    {{-- Badge publication --}}
                    <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1">
                        @if($dispositif->publicationEncours)
                            <span class="badge bg-success shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> En ligne
                            </span>
                        @else
                            <span class="badge bg-secondary shadow-sm">
                                <i class="bi bi-pause-circle me-1"></i> Hors ligne
                            </span>
                        @endif
                    </div>

                    {{-- Badge état --}}
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge {{ $dispositif->etat === 'Neuf' ? 'bg-success' : ($dispositif->etat === 'Bon' ? 'bg-primary' : 'bg-warning') }}">
                            {{ $dispositif->etat }}
                        </span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    @php $abonnementActif = $dispositif->abonnementActif; @endphp

                    <div class="mb-2">
                        <small class="text-muted text-uppercase fw-bold">
                            {{ $dispositif->type_dispositif->categorie->nom ?? '-' }}
                        </small>
                    </div>

                    <p class="card-text text-muted small mb-2">
                        {{ $dispositif->designation }}
                        @if($dispositif->numero_immatriculation)
                            <span class="badge bg-light text-dark border"># {{ $dispositif->numero_immatriculation }}</span>
                        @endif
                    </p>

                    {{-- Période abonnement --}}
                    @if($abonnementActif)
                        <div class="mb-3">
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-bell-fill me-1"></i> Abonné
                            </span>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-calendar-range me-1"></i>
                                {{ \Carbon\Carbon::parse($abonnementActif->date_debut)->format('d/m/Y') }}
                                →
                                {{ \Carbon\Carbon::parse($abonnementActif->date_fin)->format('d/m/Y') }}
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                <i class="bi bi-bell-slash me-1"></i> Aucun abonnement actif
                            </span>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-auto pt-3 border-top d-flex justify-content-between">
                        <div class="btn-group">
                            <a href="{{ route('user.dispositifs.show', $dispositif) }}"
                               class="btn btn-sm btn-outline-primary" title="Voir détails">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('user.dispositifs.edit', $dispositif) }}"
                               class="btn btn-sm btn-outline-warning" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($abonnementActif)
                                {{-- ✅ Abonnement valide : accès direct --}}
                                <a href="{{ route('user.publications.createByDispositif', $dispositif) }}"
                                   class="btn btn-sm btn-outline-success" title="Publier">
                                    <i class="bi bi-megaphone"></i>
                                    <span>Publier</span>
                                </a>
                            @else
                                {{-- ❌ Pas d'abonnement : ouvrir modal --}}
                                <button type="button"
                                        class="btn btn-sm btn-outline-success"
                                        title="Publier"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAbonnement{{ $dispositif->id }}">
                                    <i class="bi bi-megaphone"></i>
                                    <span>Publier</span>
                                </button>

                                {{-- Bouton S'abonner — uniquement si pas d'abonnement --}}
                                <a href="{{ route('user.abonnements.createByDispositif', $dispositif) }}"
                                   class="btn btn-sm btn-outline-info" title="S'abonner">
                                    <i class="bi bi-bell"></i>
                                    <span>S'abonner</span>
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('user.dispositifs.destroy', $dispositif) }}"
                              method="POST"
                              onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal abonnement requis (hors card pour éviter z-index) --}}
            @unless($abonnementActif)
                <div class="modal fade" id="modalAbonnement{{ $dispositif->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-exclamation-circle text-warning me-2"></i>
                                    Abonnement requis
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-muted">
                                    Ce matériel n'a pas d'abonnement valide. Vous pouvez vous abonner
                                    pour bénéficier des avantages, ou continuer sans abonnement.
                                </p>
                            </div>
                            <div class="modal-footer border-0 d-flex flex-column gap-2">
                                <a href="{{ route('user.abonnements.createByDispositif', $dispositif) }}"
                                   class="btn btn-success w-100">
                                    <i class="bi bi-bell me-2"></i>S'abonner
                                </a>
                                <a href="{{ route('user.publications.createByDispositif', $dispositif) }}"
                                   class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-arrow-right me-2"></i>Continuer sans abonnement
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endunless
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Aucun matériel trouvé.</p>
        </div>
    @endforelse
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $dispositifs->withQueryString()->links() }}
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/dependent-select.js') }}"></script>
@endsection
