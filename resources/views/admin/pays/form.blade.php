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

{{-- ================= INDICATEUR D'ÉTAPES ================= --}}
<div class="wizard-steps d-flex justify-content-between mb-4">
    <div class="wizard-step active" data-step="1">
        <span class="step-circle">1</span>
        <span class="step-label">Informations générales</span>
    </div>
    <div class="wizard-step" data-step="2">
        <span class="step-circle">2</span>
        <span class="step-label">Tarifs</span>
    </div>
    <div class="wizard-step" data-step="3">
        <span class="step-circle">3</span>
        <span class="step-label">Modes de paiement</span>
    </div>
</div>

<form id="paysForm" 
    method="POST" 
    action="{{ isset($pays) ? route('admin.pays.update', $pays->id) : route('admin.pays.store') }}" 
    enctype="multipart/form-data"
    data-waiting-message="Enregistrement du pays en cours...">
    @csrf
    @isset($pays) 
        @method('PUT') 
    @endisset

    {{-- ================= ÉTAPE 1 : INFOS GÉNÉRALES ================= --}}
    <div class="wizard-pane" data-step="1">

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
                <label>Libelle division <span class="text-danger">*</span></label>
                <input type="text" name="libelle_division" class="form-control"
                    value="{{ old('libelle_division', $pays->libelle_division ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label>Libellé sous division <span class="text-danger">*</span></label>
                <input type="text" name="libelle_sous_division" class="form-control"
                    value="{{ old('libelle_sous_division', $pays->libelle_sous_division ?? '') }}" required>
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
                <label>Nombre de jour minimum de publication <span class="text-danger">*</span></label>
                <input type="number" name="nb_jour_min_pub" class="form-control" step="1"
                    value="{{ old('nb_jour_min_pub', $pays->nb_jour_min_pub ?? '') }}" required>
            </div>

            <div class="col-md-4">
                <label>Bonus sponsor à l'inscription d'un filleul <span class="text-danger">*</span></label>
                <input type="number" name="bonus_sponsor" class="form-control" step="0.01"
                    value="{{ old('bonus_sponsor', $pays->bonus_sponsor ?? '') }}" required>
            </div>

            <div class="col-md-4">
                <label>Taux de commission du nouvel inscrit (%) <span class="text-danger">*</span></label>
                <input type="number" name="taux_sponsor_new" class="form-control" step="0.01"
                    value="{{ old('taux_sponsor_new', $pays->taux_sponsor_new ?? '') }}" required>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-primary" onclick="goToStep(2)">
                Suivant <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>

    {{-- ================= ÉTAPE 2 : TARIFS ================= --}}
    <div class="wizard-pane d-none" data-step="2">
        <div class="bg-orange p-2 text-white rounded d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Tarifs</h5>

            <button type="button"
                    class="btn bg-navy text-white btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#tarifModal"
                    onclick="openTarifModal()">
                <i class="bi bi-plus-lg me-2"></i>Ajouter
            </button>
        </div>

        <table class="table table-sm align-middle" id="tarifs-table">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Valeur</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="tarifs-tbody"></tbody>
        </table>

        {{-- Conteneur des vrais inputs soumis avec le formulaire --}}
        <div id="tarifs-hidden-inputs" class="d-none"></div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(1)">
                <i class="bi bi-arrow-left"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary" onclick="goToStep(3)">
                Suivant <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>

    {{-- ================= ÉTAPE 3 : MODES DE PAIEMENT ================= --}}
    <div class="wizard-pane d-none" data-step="3">

        <div class="bg-orange p-2 text-white rounded d-flex justify-content-between align-items-center mb-3">
            <h5>Modes de paiement</h5>

            <button type="button" 
            class="btn bg-navy text-white btn-sm" 
            data-bs-toggle="modal" 
            data-bs-target="#modeModal" onclick="openModeModal()">
                <i class="bi bi-plus-lg me-2"></i>Ajouter
            </button>
        </div>    

        <table class="table table-sm align-middle" id="modes-table">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Désignation</th>
                    <th>Type</th>
                    <th>URL API</th>
                    <th>N° compte</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="modes-tbody"></tbody>
        </table>

        <div id="modes-hidden-inputs" class="d-none"></div>

        <div id="form-errors" class="alert alert-danger d-none mt-3"></div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="goToStep(2)">
                <i class="bi bi-arrow-left"></i> Précédent
            </button>
            <button type="button" class="btn btn-success" onclick="submitPaysForm()">
                <i class="bi bi-save"></i> Enregistrer
            </button>
        </div>

    </div>

</form>

{{-- ================= MODAL TARIF ================= --}}
<div class="modal fade" id="tarifModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tarifModalTitle">Ajouter un tarif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Désignation</label>
                    <input type="text" id="tarifDesignation" class="form-control">
                </div>
                <div class="row g-2">
                    <div class="col-4">
                        <label>Début</label>
                        <input type="number" id="tarifDebut" class="form-control">
                    </div>
                    <div class="col-4">
                        <label>Fin</label>
                        <input type="number" id="tarifFin" class="form-control">
                    </div>
                    <div class="col-4">
                        <label>Valeur</label>
                        <input type="number" step="0.01" id="tarifValeur" class="form-control">
                    </div>
                </div>
                <div id="tarifModalError" class="text-danger small mt-2 d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="saveTarif()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL MODE DE PAIEMENT ================= --}}
<div class="modal fade" id="modeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modeModalTitle">Ajouter un mode de paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="text-center mb-3">
                    <div id="modeLogoPreview" class="mode-logo-preview mx-auto mb-2">
                        <span class="text-muted small">Aucun logo</span>
                    </div>
                    <input type="file" id="modeLogoInput" class="form-control form-control-sm" accept="image/*" onchange="previewModalLogo(this)">
                </div>

                <div class="mb-3">
                    <label>Désignation</label>
                    <input type="text" id="modeDesignation" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Type</label>
                    <select id="modeType" class="form-select">
                        <option value="">Sélectionner</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Visa Card">Visa Card</option>
                        <option value="Wallet">Wallet</option>
                        <option value="Espèce">Espèce</option>
                        <option value="Chèque">Chèque</option>
                        <option value="Virement">Virement</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>URL API</label>
                    <input type="text" id="modeApiUrl" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Numéro compte</label>
                    <input type="text" id="modeNumeroCompte" class="form-control">
                </div>

                <div id="modeModalError" class="text-danger small d-none"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="saveMode()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

@php
    $tarifsInit = [];
    if (isset($pays)) {
        foreach ($pays->tarifs as $t) {
            $tarifsInit[] = [
                'designation' => $t->designation,
                'tranche_debut' => $t->tranche_debut,
                'tranche_fin' => $t->tranche_fin,
                'tranche_valeur' => $t->tranche_valeur,
            ];
        }
    }

    $modesInit = [];
    if (isset($pays)) {
        foreach ($pays->modePaiements as $m) {
            $modesInit[] = [
                'designation' => $m->designation,
                'type' => $m->type,
                'api_url' => $m->api_url,
                'numero_compte' => $m->numero_compte,
                'logoExistant' => $m->logo,
                'logoUrl' => $m->logo ? asset('storage/' . $m->logo) : null,
            ];
        }
    }
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {

let currentStep = 1;

// =============================
// Données initiales (depuis $pays existant)
// =============================

let tarifsData = @json($tarifsInit);
let modesData = @json($modesInit);

// logoFile (objet File natif, non sérialisable via json_encode) : ajouté séparément
modesData.forEach(m => m.logoFile = null);

let tarifEditIndex = null;
let modeEditIndex = null;

// =============================
// Navigation wizard
// =============================

window.goToStep = function (step) {

    if (step > currentStep) {

        if (currentStep === 1 && !validateStep1()) {
            return;
        }

        if (currentStep === 2 && tarifsData.length === 0) {
            alert('Ajoutez au moins un tarif.');
            return;
        }

        if (currentStep === 3 && modesData.length === 0) {
            alert('Ajoutez au moins un mode de paiement.');
            return;
        }
    }

    document.querySelectorAll('.wizard-pane')
        .forEach(p => p.classList.add('d-none'));

    const target = document.querySelector(
        `.wizard-pane[data-step="${step}"]`
    );

    if (target) {
        target.classList.remove('d-none');
    }

    document.querySelectorAll('.wizard-step')
        .forEach(s => {

            const n = Number(s.dataset.step);

            s.classList.remove('active', 'completed');

            if (n === step) {
                s.classList.add('active');
            }
            else if (n < step) {
                s.classList.add('completed');
            }
        });

    currentStep = step;

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

function validateStep1() {
    const pane = document.querySelector('.wizard-pane[data-step="1"]');
    let valid = true;
    pane.querySelectorAll('[required]').forEach(el => {
        el.classList.remove('is-invalid');
        if (!el.value.trim()) { valid = false; el.classList.add('is-invalid'); }
    });
    if (!valid) alert('Veuillez remplir tous les champs obligatoires de cette étape.');
    return valid;
}

// =============================
// TARIFS — liste + modal
// =============================

function renderTarifs() {
    const tbody = document.getElementById('tarifs-tbody');
    tbody.innerHTML = tarifsData.map((t, i) => `
        <tr>
            <td>${t.designation}</td>
            <td>${t.tranche_debut}</td>
            <td>${t.tranche_fin}</td>
            <td>${t.tranche_valeur}</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editTarif(${i})"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTarif(${i})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `).join('') || `<tr><td colspan="5" class="text-muted text-center">Aucun tarif</td></tr>`;
}

window.openTarifModal = function () {
    tarifEditIndex = null;
    document.getElementById('tarifModalTitle').textContent = 'Ajouter un tarif';
    document.getElementById('tarifDesignation').value = '';
    document.getElementById('tarifDebut').value = '';
    document.getElementById('tarifFin').value = '';
    document.getElementById('tarifValeur').value = '';
    document.getElementById('tarifModalError').classList.add('d-none');
};

window.editTarif = function (i) {
    tarifEditIndex = i;
    const t = tarifsData[i];
    document.getElementById('tarifModalTitle').textContent = 'Modifier le tarif';
    document.getElementById('tarifDesignation').value = t.designation;
    document.getElementById('tarifDebut').value = t.tranche_debut;
    document.getElementById('tarifFin').value = t.tranche_fin;
    document.getElementById('tarifValeur').value = t.tranche_valeur;
    document.getElementById('tarifModalError').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('tarifModal')).show();
};

window.deleteTarif = function (i) {
    if (!confirm('Supprimer ce tarif ?')) return;
    tarifsData.splice(i, 1);
    renderTarifs();
};

window.saveTarif = function () {
    const designation = document.getElementById('tarifDesignation').value.trim();
    const debut = document.getElementById('tarifDebut').value;
    const fin = document.getElementById('tarifFin').value;
    const valeur = document.getElementById('tarifValeur').value;
    const errBox = document.getElementById('tarifModalError');

    if (!designation || !debut || !fin || !valeur) {
        errBox.textContent = 'Tous les champs sont obligatoires.';
        errBox.classList.remove('d-none');
        return;
    }
    if (Number(debut) > Number(fin)) {
        errBox.textContent = 'Le début doit être inférieur ou égal à la fin.';
        errBox.classList.remove('d-none');
        return;
    }

    // Vérifie le chevauchement avec les autres tranches (hors celle en cours d'édition)
    const overlap = tarifsData.some((t, i) => {
        if (i === tarifEditIndex) return false;
        return Number(debut) <= Number(t.tranche_fin) && Number(t.tranche_debut) <= Number(fin);
    });
    if (overlap) {
        errBox.textContent = 'Cette tranche chevauche une tranche existante.';
        errBox.classList.remove('d-none');
        return;
    }

    const item = { designation, tranche_debut: debut, tranche_fin: fin, tranche_valeur: valeur };

    if (tarifEditIndex !== null) tarifsData[tarifEditIndex] = item;
    else tarifsData.push(item);

    renderTarifs();
    bootstrap.Modal.getInstance(document.getElementById('tarifModal')).hide();
};

// =============================
// MODES DE PAIEMENT — liste + modal
// =============================

function renderModes() {
    const tbody = document.getElementById('modes-tbody');
    tbody.innerHTML = modesData.map((m, i) => {
        const logoSrc = m.logoFile ? URL.createObjectURL(m.logoFile) : m.logoUrl;
        return `
        <tr>
            <td>${logoSrc ? `<img src="${logoSrc}" class="table-logo-thumb">` : '<span class="text-muted small">—</span>'}</td>
            <td>${m.designation}</td>
            <td>${m.type}</td>
            <td>${m.api_url || ''}</td>
            <td>${m.numero_compte || ''}</td>
            <td class="text-end">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editMode(${i})"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMode(${i})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;
    }).join('') || `<tr><td colspan="6" class="text-muted text-center">Aucun mode de paiement</td></tr>`;
}

window.previewModalLogo = function (input) {
    const wrap = document.getElementById('modeLogoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => wrap.innerHTML = `<img src="${e.target.result}">`;
        reader.readAsDataURL(input.files[0]);
    }
};

window.openModeModal = function () {
    modeEditIndex = null;
    document.getElementById('modeModalTitle').textContent = 'Ajouter un mode de paiement';
    document.getElementById('modeDesignation').value = '';
    document.getElementById('modeType').value = '';
    document.getElementById('modeApiUrl').value = '';
    document.getElementById('modeNumeroCompte').value = '';
    document.getElementById('modeLogoInput').value = '';
    document.getElementById('modeLogoPreview').innerHTML = '<span class="text-muted small">Aucun logo</span>';
    document.getElementById('modeModalError').classList.add('d-none');
};

window.editMode = function (i) {
    modeEditIndex = i;
    const m = modesData[i];
    document.getElementById('modeModalTitle').textContent = 'Modifier le mode de paiement';
    document.getElementById('modeDesignation').value = m.designation;
    document.getElementById('modeType').value = m.type;
    document.getElementById('modeApiUrl').value = m.api_url || '';
    document.getElementById('modeNumeroCompte').value = m.numero_compte || '';
    document.getElementById('modeLogoInput').value = '';

    const preview = document.getElementById('modeLogoPreview');
    const src = m.logoFile ? URL.createObjectURL(m.logoFile) : m.logoUrl;
    preview.innerHTML = src ? `<img src="${src}">` : '<span class="text-muted small">Aucun logo</span>';

    document.getElementById('modeModalError').classList.add('d-none');
    new bootstrap.Modal(document.getElementById('modeModal')).show();
};

window.deleteMode = function (i) {
    if (!confirm('Supprimer ce mode de paiement ?')) return;
    modesData.splice(i, 1);
    renderModes();
};

window.saveMode = function () {
    const designation = document.getElementById('modeDesignation').value.trim();
    const type = document.getElementById('modeType').value;
    const api_url = document.getElementById('modeApiUrl').value.trim();
    const numero_compte = document.getElementById('modeNumeroCompte').value.trim();
    const fileInput = document.getElementById('modeLogoInput');
    const errBox = document.getElementById('modeModalError');

    if (!designation || !type) {
        errBox.textContent = 'Désignation et type sont obligatoires.';
        errBox.classList.remove('d-none');
        return;
    }

    const existing = modeEditIndex !== null ? modesData[modeEditIndex] : {};
    const item = {
        designation, type, api_url, numero_compte,
        logoFile: fileInput.files[0] || existing.logoFile || null,
        logoExistant: fileInput.files[0] ? null : (existing.logoExistant || null),
        logoUrl: existing.logoUrl || null,
    };

    if (modeEditIndex !== null) modesData[modeEditIndex] = item;
    else modesData.push(item);

    renderModes();
    bootstrap.Modal.getInstance(document.getElementById('modeModal')).hide();
};

// =============================
// Construction des inputs cachés avant soumission
// =============================

function buildHiddenInputs() {

    // ---- Tarifs ----
    const tarifsWrap = document.getElementById('tarifs-hidden-inputs');
    tarifsWrap.innerHTML = '';
    tarifsData.forEach((t, i) => {
        ['designation', 'tranche_debut', 'tranche_fin', 'tranche_valeur'].forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `tarifs[${i}][${field}]`;
            input.value = t[field];
            tarifsWrap.appendChild(input);
        });
    });

    // ---- Modes de paiement ----
    const modesWrap = document.getElementById('modes-hidden-inputs');
    modesWrap.innerHTML = '';
    modesData.forEach((m, i) => {
        ['designation', 'type', 'api_url', 'numero_compte'].forEach(field => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `mode_paiements[${i}][${field}]`;
            input.value = m[field] || '';
            modesWrap.appendChild(input);
        });

        if (m.logoFile) {
            // Réassigne un vrai File à un <input type="file"> généré dynamiquement
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = `mode_paiements[${i}][logo]`;
            fileInput.classList.add('d-none');

            const dt = new DataTransfer();
            dt.items.add(m.logoFile);
            fileInput.files = dt.files;

            modesWrap.appendChild(fileInput);
        } else if (m.logoExistant) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = `mode_paiements[${i}][logo_existant]`;
            hidden.value = m.logoExistant;
            modesWrap.appendChild(hidden);
        }
    });
}

// =============================
// Soumission finale
// =============================

window.submitPaysForm = function () {

    const errorBox = document.getElementById('form-errors');

    errorBox.classList.add('d-none');
    errorBox.innerHTML = '';

    let messages = [];

    // Vérification tarifs
    if (!Array.isArray(tarifsData) || tarifsData.length === 0) {
        messages.push('Ajoutez au moins un tarif.');
    }

    // Vérification modes de paiement
    if (!Array.isArray(modesData) || modesData.length === 0) {
        messages.push('Ajoutez au moins un mode de paiement.');
    }

    if (messages.length > 0) {

        errorBox.innerHTML =
            '<strong>Veuillez corriger :</strong>' +
            '<ul>' +
            messages.map(m => `<li>${m}</li>`).join('') +
            '</ul>';

        errorBox.classList.remove('d-none');

        if (tarifsData.length === 0) {
            goToStep(2);
        } else if (modesData.length === 0) {
            goToStep(3);
        }

        return;
    }

    // Construire les vrais champs POST
    buildHiddenInputs();

    console.log('Tarifs envoyés :', tarifsData);
    console.log('Modes envoyés :', modesData);

    console.log(
        'tarifs inputs = ',
        document.querySelectorAll('#tarifs-hidden-inputs input').length
    );

    console.log(
        'modes inputs = ',
        document.querySelectorAll('#modes-hidden-inputs input').length
    );

    // Soumission réelle
    const form = document.getElementById('paysForm');

    if (!form) {
        console.error('Formulaire #paysForm introuvable.');
        return;
    }

    form.submit();
};

renderTarifs();
renderModes();

});
</script>