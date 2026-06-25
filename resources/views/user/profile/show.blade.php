{{-- resources/views/user/profile/show.blade.php --}}
@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('title', 'Mon Profil')

@section('main-content')
<div class="container py-4">
    <div class="row g-4">

        {{-- Colonne gauche : carte identité --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                         style="width:90px;height:90px;">
                        <i class="bi bi-person-fill text-white" style="font-size:2.5rem;"></i>
                    </div>
                </div>

                <h5 class="fw-bold mb-0">
                    {{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                </h5>

                @if(auth()->user()->raison_sociale)
                    <div class="text-muted small mb-2">{{ auth()->user()->raison_sociale }}</div>
                @endif

                <span class="badge mb-3 {{ auth()->user()->type === 'Société' ? 'bg-primary' : 'bg-secondary' }}">
                    {{ auth()->user()->type }}
                </span>

                <div class="text-start">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted small">Code parrainage</span>
                        <span class="fw-semibold small">{{ auth()->user()->code }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted small">Email</span>
                        <span class="fw-semibold small">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted small">Solde réel</span>
                        <span class="fw-semibold">
                            {{ number_format(auth()->user()->solde_reel, 0, ',', ' ') }}
                            {{ auth()->user()->pays->devise->symbol }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted small">Bonus</span>
                        <span class="fw-semibold text-warning">
                            {{ number_format(auth()->user()->solde_bonus, 0, ',', ' ') }}
                            {{ auth()->user()->pays->devise->symbol }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted small">Total disponible</span>
                        <span class="fw-bold text-success">
                            {{ number_format(auth()->user()->solde_reel + auth()->user()->solde_bonus, 0, ',', ' ') }}
                            {{ auth()->user()->pays->devise->symbol }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('user.transactions.deposit', auth()->user()) }}"
                   class="btn btn-success btn-sm w-100 mt-3">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter des fonds
                </a>

                <button type="button"
                        class="btn btn-outline-warning btn-sm w-100 mt-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPassword">
                    <i class="bi bi-lock me-1"></i> Modifier mon mot de passe
                </button>
            </div>
        </div>

        {{-- Colonne droite : formulaire infos --}}
        <div class="col-lg-8 d-flex flex-column gap-4">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Informations personnelles --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom fw-semibold">
                    <i class="bi bi-person me-2 text-primary"></i>Informations personnelles
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row g-3">

                            {{-- Nom / Prénom --}}
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom', auth()->user()->nom) }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom"
                                       class="form-control @error('prenom') is-invalid @enderror"
                                       value="{{ old('prenom', auth()->user()->prenom) }}" required>
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Type --}}
                            <div class="col-12">
                                <label class="form-label">Type</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="type" id="type1" value="Société"
                                           {{ old('type', auth()->user()->type) === 'Société' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="type1">Société</label>

                                    <input type="radio" class="btn-check" name="type" id="type2" value="Particulier"
                                           {{ old('type', auth()->user()->type) === 'Particulier' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="type2">Particulier</label>
                                </div>
                            </div>

                            {{-- Raison sociale --}}
                            <div class="col-12">
                                <label class="form-label">Raison sociale</label>
                                <input type="text" name="raison_sociale"
                                       class="form-control @error('raison_sociale') is-invalid @enderror"
                                       value="{{ old('raison_sociale', auth()->user()->raison_sociale) }}">
                                @error('raison_sociale') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Pays --}}
                            <div class="col-md-6">
                                <label class="form-label">Pays <span class="text-danger">*</span></label>
                                <select name="pays_id"
                                        class="form-select @error('pays_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner</option>
                                    @foreach($pays as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('pays_id', auth()->user()->pays_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pays_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Téléphone / WhatsApp --}}
                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="telephone"
                                       class="form-control @error('telephone') is-invalid @enderror"
                                       value="{{ old('telephone', auth()->user()->telephone) }}" required>
                                @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">WhatsApp</label>
                                <input type="text" name="whatsapp"
                                       class="form-control @error('whatsapp') is-invalid @enderror"
                                       value="{{ old('whatsapp', auth()->user()->whatsapp) }}">
                                @error('whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal mot de passe --}}
<div class="modal fade" id="modalPassword" tabindex="-1" aria-labelledby="modalPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="modalPasswordLabel">
                    <i class="bi bi-lock me-2 text-warning"></i>Changer le mot de passe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('user.profile.password') }}" method="POST">
                @csrf @method('PUT')

                <div class="modal-body d-flex flex-column gap-3">

                    @if(session('password_error'))
                        <div class="alert alert-danger py-2">
                            {{ session('password_error') }}
                        </div>
                    @endif

                    <div>
                        <label class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="currentPwd"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('currentPwd', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="newPwd"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('newPwd', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Confirmer <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirmPwd"
                                   class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary"
                                    onclick="togglePwd('confirmPwd', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-shield-lock me-1"></i> Changer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

// Rouvrir la modal si erreur de mot de passe
@error('current_password')
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('modalPassword')).show();
    });
@enderror
@error('password')
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('modalPassword')).show();
    });
@enderror
</script>
@endpush

@endsection