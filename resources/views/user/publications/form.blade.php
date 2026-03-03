@php
    $isEdit = isset($publication);
    $dispositif = $dispositif ?? null; // sécurise la variable
    $user = auth()->user();
    $tauxPublication = $user->pays->taux_commission ?: sys_param('COMMISSION_RATE', 0);
@endphp

{{-- ERREURS --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ $isEdit ? route('user.publications.update', $publication) : route('user.publications.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- DISPOSITIF --}}
    @if(empty($dispositifs) || count($dispositifs) === 0)

        {{-- Cas dispositif imposé --}}
        <input type="hidden" name="dispositif_id" value="{{ $dispositif->id }}">

        <input type="text" class="form-control mb-3"
            value="{{ $dispositif->designation }} ({{ $dispositif->type_dispositif->nom ?? '' }})"
            disabled>

    @else

        <div class="mb-3">
            <label class="form-label">
                Dispositif <span class="text-danger">*</span>
            </label>

            @if($isEdit)

                {{-- On envoie la valeur --}}
                <input type="hidden" name="dispositif_id" value="{{ $publication->dispositif_id }}">

                {{-- On affiche mais non modifiable --}}
                <input type="text"
                    class="form-control"
                    value="{{ $publication->dispositif->designation }} ({{ $publication->dispositif->type_dispositif->nom ?? '' }})"
                    disabled>

            @else

                {{-- Mode création --}}
                <select name="dispositif_id"
                        id="dispositif_id"
                        class="form-select"
                        required>
                    <option value="">Sélectionner</option>

                    @foreach($dispositifs as $d)
                        <option value="{{ $d->id }}"
                            {{ old('dispositif_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->designation }} ({{ $d->type_dispositif->nom ?? '' }})
                        </option>
                    @endforeach
                </select>

            @endif
        </div>

    @endif

    {{-- LOCALISATION --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label>Pays</label>
            <select id="continent_id" 
                    name="pays_id"
                    data-child="region_id"
                    data-url="/regions/by-pays/"
                    class="form-select">
                <option value="">Sélectionner</option>
                @foreach($pays as $p)
                    <option value="{{ $p->id }}" {{ old('pays_id', $dispositif->user->pays_id ?? $user->pays_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label>Région</label>
            <select id="region_id" 
                    name="region_id" 
                    data-child="ville_id"
                    data-url="/villes/by-region/"
                    data-selected="{{ old('ville_id', $publication->ville_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
        </div>

        <div class="col-md-4">
            <label>Ville/Préfecture <span class="text-danger">*</span></label>
            <select id="ville_id" 
                    name="ville_id" 
                    data-selected="{{ old('ville_id', $publication->ville_id ?? '') }}"
                    class="form-select" required>
                <option value="">Sélectionner</option>
            </select>
        </div>
    </div>

    {{-- TARIF & DEVISE --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label>Tarif journalier <span class="text-danger">*</span></label>
            <input type="number"
                   step="0.01"
                   name="tarif_location"
                   id="tarif_location"
                   class="form-control"
                   value="{{ old('tarif_location', $publication->tarif_location ?? $dispositif->type_dispositif->tarif_min ?? 0) }}"
                   {{ $isEdit ? 'disabled' : '' }}
                   required>
        </div>

        <div class="col-md-6">
            <label>Devise <span class="text-danger">*</span></label>
            <select name="devise_id"
                    class="form-select"
                    {{ $isEdit ? 'disabled' : '' }}
                    required>
                <option value="">Sélectionner</option>
                @foreach($devises as $devise)
                    <option value="{{ $devise->id }}"
                        {{ old('devise_id', $publication->devise_id ?? $user->pays->devise_id ?? '') == $devise->id ? 'selected' : '' }}>
                        {{ $devise->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- DATES --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label>Date de début <span class="text-danger">*</span></label>
            <input type="date"
                   name="date_debut"
                   id="date_debut"
                   class="form-control"
                   value="{{ old('date_debut', $publication->date_debut ?? now()->toDateString()) }}"
                   {{ $isEdit ? 'disabled' : '' }}
                   required>
        </div>

        <div class="col-md-6">
            <label>Date de fin</label>
            <input type="date"
                   name="date_fin"
                   id="date_fin"
                   class="form-control readonly-field"
                   value="{{ old('date_fin', $publication->date_fin ?? now()->addDays(7)->toDateString()) }}"
                   readonly>
        </div>
    </div>

    {{-- CALCUL --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label>Prix publication</label>
            <input type="text" id="prix_publication" class="form-control readonly-field" readonly>
        </div>
        <div class="col-md-4">
            <label>Bonus accordé</label>
            <input type="text" id="bonus_accorde" class="form-control readonly-field" readonly>
        </div>
        <div class="col-md-4">
            <label>Coût publication</label>
            <input type="text" id="cout_publication" class="form-control readonly-field" readonly>
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="mt-3">
        <button class="btn btn-success">
            {{ $isEdit ? 'Enregistrer' : 'Publier' }}
        </button>
        <a href="{{ route('user.publications.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>


@section('scripts')
<script src="{{ asset('js/dependent-select.js') }}"></script>

{{-- JAVASCRIPT --}}
<script>
const TAUX = {{ $tauxPublication }};
const SOLDE_BONUS = {{ $user->solde_bonus ?? 0 }};

const tarifInput = document.getElementById('tarif_location');
const dateDebut = document.getElementById('date_debut');
const dateFin = document.getElementById('date_fin');

// On initialise le tarif min avec la valeur par défaut du type de dispositif
let currentTarifMin = {{ $dispositif->type_dispositif->tarif_min ?? 0 }};

function calculer() {
    const tarifSaisi = parseFloat(tarifInput?.value) || 0;
    
    // Protection : si currentTarifMin n'est pas encore chargé
    const minReference = typeof currentTarifMin !== 'undefined' ? currentTarifMin : 0;
    
    // On utilise le plus élevé entre le tarif saisi et le minimum autorisé
    const tarifBase = Math.max(tarifSaisi, currentTarifMin);
    
    // Calcul du prix selon le taux (commission/frais)
    const prix = tarifBase * TAUX / 100;
    
    // Calcul du bonus (ne peut pas dépasser le prix de la publication ni le solde dispo)
    const bonus = Math.min(prix, SOLDE_BONUS);
    
    // Coût final à payer
    const cout = prix - bonus;

    // Mise à jour de l'affichage
    document.getElementById('prix_publication').value = prix.toFixed(2);
    document.getElementById('bonus_accorde').value = bonus.toFixed(2);
    document.getElementById('cout_publication').value = cout.toFixed(2);
}

// Écouteurs d'événements
tarifInput?.addEventListener('input', calculer);

dateDebut?.addEventListener('change', () => {
    if (!dateDebut.value) return;
    const d = new Date(dateDebut.value);
    d.setDate(d.getDate() + 7);
    dateFin.value = d.toISOString().split('T')[0];
});

/* Mise à jour du Tarif min quand on change de dispositif */
const dispositifSelect = document.getElementById('dispositif_id');
dispositifSelect?.addEventListener('change', function () {
    if (!this.value) return;

    fetch(`/dispositifs/${this.value}/tarif-min`)
        .then(r => r.json())
        .then(data => {
            currentTarifMin = parseFloat(data.tarif_min) || 0;
            
            tarifInput.min = data.tarif_min;            
            tarifInput.value = data.tarif_min;
            
            calculer();
        });
});

// Lancement initial du calcul
calculer();
</script>
@endsection
