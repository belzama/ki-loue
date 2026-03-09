@csrf

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Continent <span class="text-danger">*</span></label>
        <select name="continent_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($continents as $c)
                <option value="{{ $c->id }}" 
                    {{ (old('continent_id', $pays->continent_id ?? '') == $c->id) ? 'selected' : '' }}>
                    {{ $c->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label>Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" 
            value="{{ old('nom', $pays->nom ?? '') }}" required>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Code ISO<span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control"
            value="{{ old('code', $pays->code ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label>Indicatif<span class="text-danger">*</span></label>
        <input type="text" name="indicatif" class="form-control"
            value="{{ old('indicatif', $pays->indicatif ?? '') }}" required>
    </div>
</div>

<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label>Devise <span class="text-danger">*</span></label>
        <select name="devise_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($devises as $d)
                <option value="{{ $d->id }}" 
                    {{ (old('devise_id', $pays->devise_id ?? '') == $d->id) ? 'selected' : '' }}>
                    {{ $d->libelle }} ({{ $d->code }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Langue officielle <span class="text-danger">*</span></label>
        <select name="langue_officielle" class="form-select" required>
            <option value="">Sélectionner</option>

            @foreach($langs as $code => $libelle)
                <option value="{{ $libelle }}"
                    {{ old('langue_officielle', $pays->langue_officielle ?? '') == $libelle ? 'selected' : '' }}>
                    {{ $libelle }} ({{ strtoupper($code) }})
                </option>
            @endforeach

        </select>
    </div>

    <div class="col-md-4">
        <label>Nationalité<span class="text-danger">*</span></label>
        <input type="text" name="nationalite" class="form-control"
            value="{{ old('nationalite', $pays->nationalite ?? '') }}" required>
    </div>
</div>

<div class="row g-3 mb-3">

    <div class="col-md-4">
        <label>Taux de commission sur les paiements <span class="text-danger">*</span></label>
        <input type="number" name="taux_commission" class="form-control" step="0.01"
            value="{{ old('taux_commission', $pays->taux_commission ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label>Bonus du sponsor à l'inscription d'un filleul <span class="text-danger">*</span></label>
        <input type="number" name="bonus_sponsor" class="form-control" step="0.01"
            value="{{ old('bonus_sponsor', $pays->bonus_sponsor ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label>Taux de commission du nouvel inscrit <span class="text-danger">*</span></label>
        <input type="number" name="taux_sponsor_new" class="form-control" step="0.01"
            value="{{ old('taux_sponsor_new', $pays->taux_sponsor_new ?? '') }}" required>
    </div>
</div>

<hr>

<h5>Modes de paiement</h5>

<div id="mode_paiements-wrapper">
    @if(isset($pays))
        @foreach($pays->modePaiements as $i => $mode)
            @include('partials.mode_paiement_row', ['index'=>$i, 'mode'=>$mode])
        @endforeach
    @else
        @include('partials.mode_paiement_row', ['index'=>0])
    @endif
</div>

<button type="button" class="btn btn-primary btn-sm mt-2" onclick="addMode()">
    + Ajouter un mode de paiement
</button>


<button type="submit" class="btn btn-success">Enregistrer</button>


<script>
let modeIndex = {{ isset($pays) ? $pays->modePaiements->count() : 1 }};

// Ajouter un nouveau mode de paiement
function addMode() {
    let wrapper = document.getElementById('mode_paiements-wrapper');

    let html = `
        <div class="row border p-2 mb-2 mode-item">

            <div class="col-md-3">
                <input type="text" name="mode_paiements[${modeIndex}][designation]"
                       class="form-control"
                       placeholder="Désignation" required>
            </div>

            <div class="col-md-3">
                <select name="mode_paiements[${modeIndex}][type]" class="form-select" required>
                    <option value="">Sélectionner le type</option>
                    <option value="Mobile Money">Mobile Money</option>
                    <option value="Visa Card">Visa Card</option>
                    <option value="Wallet">Wallet</option>
                    <option value="Espèce">Espèce</option>
                    <option value="Chèque">Chèque</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="mode_paiements[${modeIndex}][api_url]"
                       class="form-control"
                       placeholder="Url de l'API">
            </div>

            <div class="col-md-2">
                <input type="text" name="mode_paiements[${modeIndex}][numero_compte]"
                       class="form-control"
                       placeholder="Numéro de compte">
            </div>

            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="removeMode(this)" title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    modeIndex++;
}

// Supprimer un mode
function removeMode(button) {
    button.closest('.mode-item').remove();
}

// Vérifier les modes avant la soumission
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    let messages = [];

    document.querySelectorAll('.mode-item').forEach((row, idx) => {
        let designation = row.querySelector('input[name*="[designation]"]').value.trim();
        let type = row.querySelector('select[name*="[type]"]').value.trim();

        if (!designation) {
            valid = false;
            messages.push(`Mode #${idx + 1} : la désignation est obligatoire.`);
        }
        if (!type) {
            valid = false;
            messages.push(`Mode #${idx + 1} : le type est obligatoire.`);
        }
    });

    if (!valid) {
        e.preventDefault();
        alert(messages.join("\n"));
    }
});

// Bootstrap tooltip init
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>