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
                    data-url="{{ url('regions/by-pays') }}/"
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
                    data-child="departement_id"
                    data-url="{{ url('departements/by-region') }}/"
                    data-selected="{{ old('departement_id', $publication->departement_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
        </div>

        <div class="col-md-4">
            <label>Préfecture/Département <span class="text-danger">*</span></label>
            <select id="departement_id" 
                    name="departement_id" 
                    data-selected="{{ old('departement_id', $publication->departement_id ?? '') }}"
                    class="form-select" required>
                <option value="">Sélectionner</option>
            </select>
        </div>
    </div>
    
    {{-- VILLE / LOCALITE --}}
    <div class="mb-3">
        <label>Ville/Localité <span class="text-danger">*</span></label>
        <input type="text" name="ville" class="form-control"
                   value="{{ old('ville', $publication->ville ?? '') }}" required>
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
                    class="form-control"
                    value="{{ old('date_fin', $publication->date_fin ?? now()->addDays($nbJourMinPub)->toDateString()) }}"
                    require>
            </div>
        </div>

        {{-- CALCUL --}}
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label>Durée (jours)</label>
                <input type="text" id="nb_jours" name="nb_jours" class="form-control readonly-field" readonly>
            </div>
            <div class="col-md-3">
                <label>Prix publication</label>
                <input type="text" id="prix_publication" name="prix_publication" class="form-control readonly-field" readonly>
            </div>
            <div class="col-md-3">
                <label>Bonus accordé</label>
                <input type="text" id="bonus_accorde" name="bonus_accorde" class="form-control readonly-field" readonly>
            </div>
            <div class="col-md-3">
                <label>Coût publication</label>
                <input type="text" id="cout_publication" name="cout_publication" class="form-control readonly-field" readonly>
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

const SOLDE_BONUS = {{ $user->solde_bonus ?? 0 }};
const PAYS_ID = {{ $user->pays_id }};
const baseUrl = "{{ url('/') }}";

const OLD_PAYS   = "{{ old('pays_id', $publication->dispositif->user->pays_id ?? $dispositif->user->pays_id ?? $user->pays_id ?? '') }}";
const OLD_REGION = "{{ old('region_id',$publication->departement->region_id ?? $publication->region_id ?? '') }}";
const OLD_VILLE  = "{{ old('departement_id', $publication->departement_id ?? '') }}";

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

    const tarifBase = Math.max(tarifSaisi, currentTarifMin);

    const jours = diffDays(date_debut, date_fin);

    if (jours <= 0)
        return;

    document.getElementById("nb_jours").value = jours;

    let prix = 0;
    let html = '';

    tarifs.forEach(t => {

        if (jours < t.tranche_debut)
            return;

        const joursTranche = Math.min(jours, t.tranche_fin) - t.tranche_debut + 1;

        if (joursTranche > 0)
        {
            const montant = joursTranche * (tarifBase * t.tranche_valeur);

            prix += montant;

            html += `
                <tr>
                    <td>${t.tranche_debut} - ${t.tranche_fin}</td>
                    <td>${joursTranche}</td>
                    <td>${(t.tranche_valeur * 100).toFixed(2)} %</td>
                    <td>${montant.toFixed(2)}</td>
                </tr>
            `;
        }

    });

    document.getElementById("detail_tranches").innerHTML = html;

    const bonus = Math.min(prix, SOLDE_BONUS);
    const cout = prix - bonus;

    document.getElementById('prix_publication').value = prix.toFixed(2);
    document.getElementById('bonus_accorde').value = bonus.toFixed(2);
    document.getElementById('cout_publication').value = cout.toFixed(2);
}


/* =================================
   Restaurer localisation
================================= */
async function restoreLocalisation()
{
    if (!OLD_PAYS) return;

    const paysSelect = document.getElementById('continent_id');
    const regionSelect = document.getElementById('region_id');
    const departementSelect = document.getElementById('departement_id');

    paysSelect.value = OLD_PAYS;

    // Charger régions
    const res = await fetch(`${baseUrl}/regions/by-pays/${OLD_PAYS}`);
    const regions = await res.json();

    regionSelect.innerHTML = '<option value="">Sélectionner</option>';

    regions.forEach(r => {
        regionSelect.innerHTML += `<option value="${r.id}">${r.nom}</option>`;
    });

    if (OLD_REGION)
    {
        regionSelect.value = OLD_REGION;

        // Charger departements
        const res2 = await fetch(`${baseUrl}/departements/by-region/${OLD_REGION}`);
        const departements = await res2.json();

        departementSelect.innerHTML = '<option value="">Sélectionner</option>';

        departements.forEach(v => {
            departementSelect.innerHTML += `<option value="${v.id}">${v.nom}</option>`;
        });

        if (OLD_VILLE)
            departementSelect.value = OLD_VILLE;
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

    restoreLocalisation();

    appliquerContraintesDates();

    calculer();

});

</script>
@endsection
