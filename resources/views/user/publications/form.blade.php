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
                <option value="" data-division="Région" data-sous-division="Préfecture">Sélectionner</option>
                @foreach($pays as $p)
                    <option value="{{ $p->id }}" 
                        data-division="{{ $p->libelle_division }}"
                        data-sous-division="{{ $p->libelle_sous_division }}"
                        {{ old('pays_id', $dispositif->user->pays_id ?? $user->pays_id ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->nom }}
                    </option>
                @endforeach
            </select>            
            <div class="invalid-feedback" id="error-pays_id"></div>
        </div>

        <div class="col-md-4">
            <label id="label_division">{{ $dispositif->user->pays->libelle_division ?? $user->pays->libelle_division ?? 'Région' }}</label>                        
            <select id="region_id" 
                    name="region_id" 
                    data-child="departement_id"
                    data-url="{{ url('departements/by-region') }}/"
                    data-selected="{{ old('region_id', $publication->region_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
            <div class="invalid-feedback" id="error-region_id"></div>
        </div>

        <div class="col-md-4">
            <label id="label_sous_division">{{ $dispositif->user->pays->libelle_sous_division ?? $user->pays->libelle_sous_division ?? 'Préfecture' }} <span class="text-danger">*</span></label>                        
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
                <label>Date de fin <span class="text-danger">*</span></label>
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

        <div id="bloc_simulation" class="card mt-3">
            <div class="card-header">
                Profitez de la réduction sur la durée de publication
            </div>

            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Nb jours</th>
                            <th>Montant</th>
                        </tr>
                    </thead>

                    <tbody id="simulation_tranches"></tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 mb-3">
            <div class="d-flex justify-content-start">
                <button id="btn_toggle_detail" type="button"
                    class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-eye"></i>
                    <span>Voir le détail de calcul</span>
                </button>
            </div>
        </div>

        <div id="bloc_detail_calcul" class="card mt-3"  style="display:none;">
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

const bloc = document.getElementById("bloc_simulation");
const tableBody = document.getElementById("simulation_tranches");

const blocDetail = document.getElementById("bloc_detail_calcul");
const btnDetail = document.getElementById("btn_toggle_detail");

btnDetail?.addEventListener('click', () => {

    if (blocDetail.style.display === "none" || blocDetail.style.display === "") {
        blocDetail.style.display = "block";
        btnDetail.innerText = "Masquer le détail de calcul";
    } else {
        blocDetail.style.display = "none";
        btnDetail.innerText = "Voir le détail de calcul";
    }
});

const blocSimulation = document.getElementById("bloc_simulation");
const btnSimulation = document.getElementById("btn_toggle_simulation");

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
        
        console.log("Tarifs chargés:", tarifs);

        calculer();
        calculerSimulation(); // ✅ IMPORTANT

    } catch (e) {
        console.error("Erreur chargement tarifs :", e);
    }
}

/* =================================
   Diff jours
================================= */
function diffDays(date1, date2)
{
    const d1 = new Date(date1);
    const d2 = new Date(date2);
    return Math.floor((d2 - d1) / (1000 * 60 * 60 * 24));
}

/* =================================
   Contraintes dates
================================= */
function appliquerContraintesDates()
{
    const debut = dateDebutInput?.value;
    if (!debut) return;

    dateFinInput.min = debut;

    const maxDate = new Date(debut);
    maxDate.setDate(maxDate.getDate() + 365);

    dateFinInput.max = maxDate.toISOString().split('T')[0];

    if (dateFinInput.value && dateFinInput.value < debut)
        dateFinInput.value = '';
}

/* =================================
   Calcul principal
================================= */
function calculer()
{
    const tarifSaisi = parseFloat(tarifInput?.value) || 0;
    const date_debut = dateDebutInput?.value;
    const date_fin = dateFinInput?.value;

    if (!tarifSaisi || !date_debut || !date_fin || tarifs.length === 0)
        return;

    const jours = diffDays(date_debut, date_fin);
    document.getElementById("nb_jours").value = jours;

    if (jours <= 0) return;

    let prixTotal = 0;
    let htmlTranches = '';
    const baseCalcul = Math.max(tarifSaisi, currentTarifMin);

    tarifs.forEach(t => {

        if (jours < t.tranche_debut) return;

        const joursDansTranche = Math.min(jours, t.tranche_fin) - t.tranche_debut + 1;

        if (joursDansTranche > 0) {
            const montantTranche = joursDansTranche * (baseCalcul * t.tranche_valeur);

            prixTotal += montantTranche;

            htmlTranches += `
                <tr>
                    <td>${t.designation} (${t.tranche_debut} - ${t.tranche_fin})</td>
                    <td>${joursDansTranche}</td>
                    <td>${(t.tranche_valeur * 100).toFixed(2)} %</td>
                    <td>${montantTranche.toFixed(2)}</td>
                </tr>
            `;
        }
    });

    document.getElementById("detail_tranches").innerHTML = htmlTranches;

    // 🔥 gestion affichage détail
    if (!htmlTranches) {
        blocDetail.style.display = 'none';
        btnDetail.style.display = 'none';
    } else {
        btnDetail.style.display = 'inline-block';
    }

    const bonus = Math.min(prixTotal, SOLDE_BONUS);
    const cout = prixTotal - bonus;

    document.getElementById('prix_publication').value = prixTotal.toFixed(2);
    document.getElementById('bonus_accorde').value = bonus.toFixed(2);
    document.getElementById('cout_publication').value = cout.toFixed(2);

    calculerSimulation();
}

/* =================================
   Simulation
================================= */
function getPaliersDynamiques() {

    const joursActuels = parseInt(document.getElementById("nb_jours")?.value) || 0;

    if (!joursActuels) return [];

    const trancheActuelle = tarifs.find(t => 
        joursActuels >= t.tranche_debut && joursActuels <= t.tranche_fin
    );

    const trancheMax = tarifs.find(t =>
        31 >= t.tranche_debut && 31 <= t.tranche_fin
    )?.tranche_fin ?? 31;

    // Filtrer jusqu’à 31 jours
    const tranchesFiltrees = tarifs.filter(t => t.tranche_fin <= trancheMax);

    const paliers = tranchesFiltrees
        .map(t => Math.min(t.tranche_fin, 31)) // 🔥 important
        .filter(fin => !trancheActuelle || fin > trancheActuelle.tranche_fin);

    return [...new Set(paliers)]; // éviter doublons
}

function calculerSimulation() {

    const tarifSaisi = parseFloat(tarifInput?.value) || 0;
    const date_debut = dateDebutInput?.value;

    // 🔴 Conditions bloquantes
    if (!tarifSaisi || !date_debut || tarifs.length === 0) {
        bloc.style.display = 'none';
        return;
    }

    const baseCalcul = Math.max(tarifSaisi, currentTarifMin);

    const paliers = getPaliersDynamiques();

    console.log("DEBUG paliers:", paliers);

    // 🔴 Aucun palier → on cache
    if (!paliers || paliers.length === 0) {
        bloc.style.display = 'none';
        tableBody.innerHTML = '';
        return;
    }

    bloc.style.display = 'block';

    let html = '';
    let meilleurPalier = null;
    let maxEconomie = 0;

    const dateDebutObj = new Date(date_debut);
    const dateDebutStr = dateDebutObj.toLocaleDateString('fr-FR');

    const resultats = [];

    // 🔹 Calcul pour chaque palier
    paliers.forEach(nbJours => {

        let prixReduit = 0;

        tarifs.forEach(t => {

            // ignorer les tranches hors limite
            if (t.tranche_debut > 31) return;

            const trancheFinEffective = Math.min(t.tranche_fin, 31);
            
            // pas concerné par cette tranche
            if (nbJours < t.tranche_debut) return;

            const debut = t.tranche_debut;
            const fin = trancheFinEffective;
            
            // si aucune intersection avec nbJours
            if (nbJours < debut) return;

            const joursDansTranche = Math.min(nbJours, fin) - debut + 1;

            if (joursDansTranche > 0) {
                prixReduit += joursDansTranche * (baseCalcul * t.tranche_valeur);
            }
        });

        const prixNormal = nbJours * baseCalcul;
        const economie = prixNormal - prixReduit;
        const pourcentage = prixNormal > 0 ? (economie / prixNormal) * 100 : 0;

        if (economie > maxEconomie) {
            maxEconomie = economie;
            meilleurPalier = nbJours;
        }

        const d = new Date(date_debut);
        d.setDate(d.getDate() + nbJours);

        resultats.push({
            nbJours,
            prixReduit,
            prixNormal,
            economie,
            pourcentage,
            dateFin: d.toLocaleDateString('fr-FR')
        });
    });

    // 🔹 Génération HTML
    resultats.forEach(r => {

        const isBest = r.nbJours === meilleurPalier;

        html += `
            <tr class="${isBest ? 'table-success fw-bold' : ''}">
                <td>${dateDebutStr}</td>
                <td>${r.dateFin}</td>
                <td>
                    ${r.nbJours}
                    ${isBest ? '<span class="badge bg-success ms-1">🔥 Recommandé</span>' : ''}
                </td>
                <td>${r.prixReduit.toLocaleString()}</td>
                <!--td class="text-success">
                    -${r.pourcentage.toFixed(2)}%
                </td-->
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}

/* =================================
   Events
================================= */

tarifInput?.addEventListener('input', () => {
    calculer();
    calculerSimulation();
});

dateDebutInput?.addEventListener('change', () => {

    if (!dateDebutInput.value) return;

    const d = new Date(dateDebutInput.value);
    d.setDate(d.getDate() + {{ $nbJourMinPub }});
    dateFinInput.value = d.toISOString().split('T')[0];

    appliquerContraintesDates();

    calculer();
    calculerSimulation();
});

dateFinInput?.addEventListener('change', calculer);

/* =================================
   Init
================================= */

document.addEventListener('DOMContentLoaded', () => {

    if (bloc) bloc.style.display = 'none';

    chargerTarifs(PAYS_ID);

    appliquerContraintesDates();

    calculer();

});

</script>
@endsection
