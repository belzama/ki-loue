@php
    $isEdit = isset($publication);
    $dispositif = $dispositif ?? null; // sécurise la variable
    $user = auth()->user();
    $nbJourMinPub = $user->pays->nb_jour_min_pub ?: sys_param('MIN_PUB_DAYS', 1);
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
      id="publicationForm"
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
                Matériel <span class="text-danger">*</span>
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
                        >
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
    <div class="invalid-feedback" id="error-dispositif_id"></div>

    {{-- LOCALISATION --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label>Pays</label>
            <select id="pays_id" 
                    name="pays_id"
                    data-child="region_id"
                    data-url="{{ url('regions/by-pays') }}/"
                    class="form-select">
                <option value="">Sélectionner</option>
                @foreach($pays as $p)
                    <option value="{{ $p->id }}" {{ old('pays_id', $dispositif->user->pays_id ?? $user->pays_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->nom }}</option>
                @endforeach
            </select>            
            <div class="invalid-feedback" id="error-pays_id"></div>
        </div>

        <div class="col-md-4">
            <label>Région</label>
            <select id="region_id" 
                    name="region_id" 
                    data-child="departement_id"
                    data-url="{{ url('departements/by-region') }}/"
                    data-selected="{{ old('departement_id', $publication->departement_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
            <div class="invalid-feedback" id="error-region_id"></div>
        </div>

        <div class="col-md-4">
            <label>Préfecture/Département <span class="text-danger">*</span></label>
            <select id="departement_id" 
                    name="departement_id" 
                    data-selected="{{ old('departement_id', $publication->departement_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
            <div class="invalid-feedback" id="error-departement_id"></div>
        </div>
    </div>
    
    {{-- VILLE / LOCALITE --}}
    <div class="mb-3">
        <label>Ville/Localité <span class="text-danger">*</span></label>
        <input type="text" id="ville" name="ville" class="form-control"
                   value="{{ old('ville', $publication->ville ?? '') }}">
        <div class="invalid-feedback" id="error-ville"></div>
    </div>

    @if (!$isEdit)
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
                    >
                    <div class="invalid-feedback" id="error-tarif_location"></div>
            </div>

            <div class="col-md-6">
                <label>Devise <span class="text-danger">*</span></label>
                <select name="devise_id"
                        class="form-select"
                        {{ $isEdit ? 'disabled' : '' }}
                        >
                    <option value="">Sélectionner</option>
                    @foreach($devises as $devise)
                        <option value="{{ $devise->id }}"
                            {{ old('devise_id', $publication->devise_id ?? $user->pays->devise_id ?? '') == $devise->id ? 'selected' : '' }}>
                            {{ $devise->libelle }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="error-devise_id"></div>
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
                    >
                    <div class="invalid-feedback" id="error-date_debut"></div>
            </div>

            <div class="col-md-6">
                <label>Date de fin</label>
                <input type="date"
                    name="date_fin"
                    id="date_fin"
                    class="form-control"
                    value="{{ old('date_fin', $publication->date_fin ?? now()->addDays($nbJourMinPub)->toDateString()) }}"
                    >
                    <div class="invalid-feedback" id="error-date_fin"></div>
            </div>
        </div>

        {{-- CALCUL --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label>Durée (jours)</label>
                <input type="text" id="nb_jours" name="nb_jours" class="form-control bg-light readonly-field" readonly>
            </div>
            <div class="col-md-3">
                <label>Prix publication</label>
                <input type="text" id="prix_publication" name="prix_publication" class="form-control bg-light readonly-field" readonly>
            </div>
            <div class="col-md-3">
                <label>Bonus accordé</label>
                <input type="text" id="bonus_accorde" name="bonus_accorde" class="form-control bg-light readonly-field" readonly>
            </div>
            <div class="col-md-3">
                <label>Coût publication</label>
                <input type="text" id="cout_publication" name="cout_publication" class="form-control bg-font-weight-bold readonly-field" readonly>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                Détail du calcul
            </div>

            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Tranche</th>
                            <th>Jours</th>
                            <th>Taux</th>
                            <th>Montant</th>
                        </tr>
                    </thead>

                    <tbody id="detail_tranches">
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ACTIONS --}}
    <div class="mt-3">
        <button type="submit" class="btn btn-success">
            {{ $isEdit ? 'Enregistrer' : 'Publier' }}
        </button>
        <a href="{{ route('user.publications.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>


@section('scripts')
<script src="{{ asset('js/dependent-select.js') }}"></script>

{{-- JAVASCRIPT --}}
<script>

const SOLDE_BONUS = {{ $user->solde_bonus ?? 0 }};
const PAYS_ID = {{ $user->pays_id }};
const baseUrl = "{{ url('/') }}";

const OLD_PAYS   = "{{ old('pays_id', $publication->dispositif->user->pays_id ?? $dispositif->user->pays_id ?? $user->pays_id ?? '') }}";
const OLD_REGION = "{{ old('region_id',$publication->departement->region_id ?? $publication->region_id ?? '') }}";
const OLD_DEPARTEMENT  = "{{ old('departement_id', $publication->departement_id ?? '') }}";

const tarifInput = document.getElementById('tarif_location');
const dateDebutInput = document.getElementById('date_debut');
const dateFinInput = document.getElementById('date_fin');

let currentTarifMin = {{ $dispositif->type_dispositif->tarif_min ?? 0 }};
let tarifs = [];


/* =================================
   Charger les tarifs du pays
================================= */
async function chargerTarifs(pays_id)
{
    try {

        const res = await fetch(`${baseUrl}/pays/${pays_id}/tarifs`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });

        if (!res.ok)
            throw new Error("HTTP " + res.status);

        const data = await res.json();

        tarifs = Array.isArray(data) ? data : [];

        calculer();

    } catch (e) {

        console.error("Erreur chargement tarifs :", e);

    }
}


/* =================================
   Calcul nombre de jours
================================= */
function diffDays(date1, date2)
{
    const d1 = new Date(date1);
    const d2 = new Date(date2);

    const diff = d2 - d1;

    return Math.floor(diff / (1000 * 60 * 60 * 24));
}


/* =================================
   Appliquer contraintes dates
================================= */
function appliquerContraintesDates()
{
    const debut = dateDebutInput?.value;

    if (!debut) return;

    // Date minimum fin
    dateFinInput.min = debut;

    // Date max = 365 jours
    const maxDate = new Date(debut);
    maxDate.setDate(maxDate.getDate() + 365);

    dateFinInput.max = maxDate.toISOString().split('T')[0];

    // Si date fin invalide
    if (dateFinInput.value && dateFinInput.value < debut)
        dateFinInput.value = '';
}


/* =================================
   Calcul publication
================================= */
function calculer()
{
    const tarifSaisi = parseFloat(tarifInput?.value) || 0;
    const date_debut = dateDebutInput?.value;
    const date_fin = dateFinInput?.value;

    if (!tarifSaisi || !date_debut || !date_fin || tarifs.length === 0)
        return;

    const jours = diffDays(date_debut, date_fin);
    //const jours = Math.max(0, diffDays(date_debut, date_fin));
    document.getElementById("nb_jours").value = jours;

    if (jours <= 0)
        return;

    let prixTotal = 0;
    let htmlTranches = '';
    const baseCalcul = Math.max(tarifSaisi, currentTarifMin);

    tarifs.forEach(t => {

        if (jours < t.tranche_debut)
            return;

        const joursDansTranche = Math.min(jours, t.tranche_fin) - t.tranche_debut + 1;

        if (joursDansTranche > 0)
        {
            const montantTranche = joursDansTranche * (baseCalcul * t.tranche_valeur);

            prixTotal += montantTranche;

            htmlTranches += `
                <tr>
                    <td>${t.tranche_debut} - ${t.tranche_fin}</td>
                    <td>${joursDansTranche}</td>
                    <td>${(t.tranche_valeur * 100).toFixed(2)} %</td>
                    <td>${montantTranche.toFixed(2)}</td>
                </tr>
            `;
        }

    });

    document.getElementById("detail_tranches").innerHTML = htmlTranches;

    const bonus = Math.min(prixTotal, SOLDE_BONUS);
    const cout = prixTotal - bonus;

    document.getElementById('prix_publication').value = prixTotal.toFixed(2);
    document.getElementById('bonus_accorde').value = bonus.toFixed(2);
    document.getElementById('cout_publication').value = cout.toFixed(2);
}


/* =================================
   Restaurer localisation
================================= */
async function initLocalisation() {
    const paysSelect = document.getElementById('pays_id');
    const regionSelect = document.getElementById('region_id');
    const deptSelect = document.getElementById('departement_id');

    if (!OLD_PAYS) return;

    // 1. Charger Régions
    const regions = await fetch(`${baseUrl}/regions/by-pays/${OLD_PAYS}`).then(r => r.json());
    regionSelect.innerHTML = '<option value="">Sélectionner</option>';
    regions.forEach(r => {
        const sel = r.id == OLD_REGION ? 'selected' : '';
        regionSelect.innerHTML += `<option value="${r.id}" ${sel}>${r.nom}</option>`;
    });

    // 2. Charger Départements
    if (OLD_REGION) {
        const depts = await fetch(`${baseUrl}/departements/by-region/${OLD_REGION}`).then(r => r.json());
        deptSelect.innerHTML = '<option value="">Sélectionner</option>';
        depts.forEach(d => {
            const sel = d.id == OLD_DEPT ? 'selected' : '';
            deptSelect.innerHTML += `<option value="${d.id}" ${sel}>${d.nom}</option>`;
        });
    }
}


/* =================================
   Événements
================================= */

tarifInput?.addEventListener('input', calculer);

dateDebutInput?.addEventListener('change', () => {

    if (!dateDebutInput.value) return;

    const d = new Date(dateDebutInput.value);

    d.setDate(d.getDate() + {{ $nbJourMinPub }});

    dateFinInput.value = d.toISOString().split('T')[0];

    appliquerContraintesDates();

    calculer();
});

dateFinInput?.addEventListener('change', calculer);


/* =================================
   Changement dispositif
================================= */

const dispositifSelect = document.getElementById('dispositif_id');

dispositifSelect?.addEventListener('change', function ()
{
    if (!this.value) return;

    fetch(`${baseUrl}/dispositifs/${this.value}/tarif-min`)
        .then(r => r.json())
        .then(data => {

            currentTarifMin = parseFloat(data.tarif_min) || 0;

            tarifInput.min = currentTarifMin;
            tarifInput.value = currentTarifMin;

            calculer();
        });
});


/* =================================
   Initialisation
================================= */

document.addEventListener('DOMContentLoaded', () => {

    chargerTarifs(PAYS_ID);

    initLocalisation();

    appliquerContraintesDates();

    calculer();

});

/* =================================
   Soumission du Formulaire (Unique)
================================= */
if (typeof publicationHandlerAttached === 'undefined') {
    window.publicationHandlerAttached = true;

    document.addEventListener('submit', async function(e) {
        // On vérifie que c'est bien notre formulaire
        if (e.target && e.target.id === 'publicationForm') {
            e.preventDefault();
            e.stopImmediatePropagation(); // Stop les autres scripts (comme ajax-form s'il restait des traces)

            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);

            // 1. Réinitialisation
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => {
                el.innerText = '';
                el.style.display = 'none';
            });

            // 2. Verrouillage
            btn.disabled = true;
            const originalText = btn.innerText;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Envoi...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json', // Précise au serveur qu'on veut du JSON
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    // Succès : Redirection vers le dépôt ou l'index
                    window.location.href = result.redirect || "{{ route('user.publications.index') }}";
                } 
                else if (response.status === 422) {
                    btn.disabled = false;
                    btn.innerText = originalText;

                    // CAS SPÉCIAL : Redirection forcée (ex: solde insuffisant)
                    if (result.redirect) {
                        window.location.href = result.redirect;
                        return;
                    }

                    // Affichage des erreurs sous les champs
                    const errors = result.errors;
                    for (const field in errors) {
                        const sanitizedField = field.replace(/\./g, '_');
                        // On cherche par ID ou par Name
                        const input = document.getElementById(field) || document.getElementsByName(field)[0];
                        const errorDiv = document.getElementById('error-' + sanitizedField);

                        if (input) input.classList.add('is-invalid');
                        if (errorDiv) {
                            errorDiv.innerText = errors[field][0];
                            errorDiv.style.display = 'block';
                        }
                    }
                    
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } 
                else {
                    throw new Error(result.message || 'Erreur serveur (500)');
                }

            } catch (error) {
                btn.disabled = false;
                btn.innerText = originalText;
                console.error(error);
                alert("Une erreur est survenue : " + error.message);
            }
        }
    });
}

</script>
@endsection
