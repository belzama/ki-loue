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
            <label class="form-label fw-bold">
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
                    <option value=""data-tarif="0">Sélectionner</option>

                    @foreach($dispositifs as $d)
                        <option value="{{ $d->id }}"
                                data-tarif="{{ $d->type_dispositif->tarif_min ?? 0 }}"
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
            <label class="form-label fw-semibold">Pays</label>
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
            <label class="form-label fw-semibold" id="label_division">{{ $dispositif->user->pays->libelle_division ?? $user->pays->libelle_division ?? 'Région' }}</label>
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
            <label class="form-label fw-semibold" id="label_sous_division">{{ $dispositif->user->pays->libelle_sous_division ?? $user->pays->libelle_sous_division ?? 'Préfecture' }} <span class="text-danger">*</span></label>
            <select id="departement_id"
                    name="departement_id"
                    data-selected="{{ old('departement_id', $publication->departement_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
            <div class="invalid-feedback" id="error-departement_id"></div>
        </div>
    </div>

    {{-- VILLE / LOCALITE & TATIF--}}
    <div class="row g-3 mb-3">
        {{-- VILLE / LOCALITE --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ville/Localité <span class="text-danger">*</span></label>
            <input type="text" id="ville" name="ville" class="form-control"
                       value="{{ old('ville', $publication->ville ?? '') }}">
            <div class="invalid-feedback" id="error-ville"></div>
        </div>

        {{-- TARIF & DEVISE --}}
        @if (!$isEdit)
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tarif journalier <span class="text-danger">*</span></label>
                <div class="input-group">
                    {{-- Champ visuel avec formateur --}}
                    <input type="text"
                           id="tarif_location_mask"
                           class="form-control fw-semibold"
                           value="{{ number_format(old('tarif_location', $publication->tarif_location ?? $dispositif->type_dispositif->tarif_min ?? 0), 0, ',', ' ') }}"
                        {{ $isEdit ? 'disabled' : '' }}>

                    {{-- Champ réel envoyé au serveur --}}
                    <input type="hidden"
                           name="tarif_location"
                           id="tarif_location"
                           value="{{ old('tarif_location', $publication->tarif_location ?? $dispositif->type_dispositif->tarif_min ?? 0) }}">

                    <span class="input-group-text fw-bold" id="display_devise">
                            {{ $user->pays->devise->symbol ?? 'FCFA' }}
                        </span>
                </div>
                <div class="invalid-feedback" id="error-tarif_location"></div>
            </div>
        @endif
    </div>

    @if (!$isEdit)
        {{-- DATES --}}
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Date de début <span class="text-danger">*</span></label>
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
                <label class="form-label fw-semibold">Date de fin <span class="text-danger">*</span></label>
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
                <label class="form-label fw-semibold">Durée (jours)</label>
                <input type="text" id="nb_jours" name="nb_jours" class="form-control bg-light readonly-field fw-bold" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Prix publication</label>
                <input type="text" id="prix_publication" name="prix_publication" class="form-control bg-light readonly-field fw-bold" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Bonus accordé</label>
                <input type="text" id="bonus_accorde" name="bonus_accorde" class="form-control bg-light readonly-field fw-bold" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Coût publication</label>
                <input type="text" id="cout_publication" name="cout_publication" class="form-control bg-font-weight-bold readonly-field fw-bold" readonly>
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

@include('user.publications.confirm_modal')
@section('scripts')
<script src="{{ asset('js/dependent-select.js') }}"></script>

{{-- JAVASCRIPT --}}
<script>

const bloc = document.getElementById("bloc_simulation");
const tableBody = document.getElementById("simulation_tranches");

const blocDetail = document.getElementById("bloc_detail_calcul");
const btnDetail = document.getElementById("btn_toggle_detail");

const dispositifSelect = document.getElementById('dispositif_id');

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
   Formatage des milliers (Espace)
================================= */
function formatMoney(amount) {
    return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

/* =================================
   Calcul principal
================================= */
function calculer()
{
    const tarifSaisi = parseFloat(tarifInput?.value) || 0;
    const baseCalcul = Math.max(tarifSaisi, currentTarifMin);

    // Si on est en création et qu'aucun tarif n'est encore dans l'input, on met le min
    if (tarifInput && !tarifInput.value) {
        tarifInput.value = currentTarifMin;
    }

    const date_debut = dateDebutInput?.value;
    const date_fin = dateFinInput?.value;

    // Validation de base
    if (!tarifSaisi || !date_debut || !date_fin || tarifs.length === 0)
        return;

    const jours = diffDays(date_debut, date_fin);
    document.getElementById("nb_jours").value = jours;

    if (jours <= 0) return;

    let prixTotal = 0;
    let htmlTranches = '';

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
                    <td>${formatMoney(montantTranche)}</td>
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

    document.getElementById('prix_publication').value = formatMoney(prixTotal);
    document.getElementById('bonus_accorde').value = formatMoney(bonus);
    document.getElementById('cout_publication').value = formatMoney(cout);

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
    const joursActuels = parseInt(document.getElementById("nb_jours")?.value) || 0;

    if (!tarifSaisi || !date_debut || tarifs.length === 0 || joursActuels <= 0) {
        if(bloc) bloc.style.display = 'none';
        return;
    }

    const baseCalcul = Math.max(tarifSaisi, currentTarifMin);

    // 1. Trouver la tranche actuelle
    const trancheActuelle = tarifs.find(t => joursActuels >= t.tranche_debut && joursActuels <= t.tranche_fin);

    // 2. Identifier la toute dernière tranche pour ne pas l'ignorer
    const maxTrancheGlobale = Math.max(...tarifs.map(t => t.tranche_fin));

    let paliers = [];

    // Ajout de la fin de la tranche actuelle si on n'y est pas encore (Point 1 & 2)
    if (trancheActuelle && joursActuels < trancheActuelle.tranche_fin) {
        paliers.push(trancheActuelle.tranche_fin);
    }

    // Ajout des fins de toutes les tranches supérieures (incluant la dernière)
    tarifs.forEach(t => {
        if (t.tranche_fin > (trancheActuelle?.tranche_fin || joursActuels)) {
            paliers.push(t.tranche_fin);
        }
    });

    // Supprimer les doublons et trier
    paliers = [...new Set(paliers)].sort((a, b) => a - b);

    if (paliers.length === 0) {
        bloc.style.display = 'none';
        return;
    }

    bloc.style.display = 'block';
    let html = '';
    let resultats = [];

    paliers.forEach(nbJours => {
        let prixReduit = 0;
        tarifs.forEach(t => {
            if (nbJours < t.tranche_debut) return;
            const joursDansTranche = Math.min(nbJours, t.tranche_fin) - t.tranche_debut + 1;
            if (joursDansTranche > 0) {
                prixReduit += joursDansTranche * (baseCalcul * t.tranche_valeur);
            }
        });

        const prixParJour = prixReduit / nbJours;
        const d = new Date(date_debut);
        d.setDate(d.getDate() + nbJours);

        resultats.push({
            nbJours,
            prixReduit,
            prixParJour,
            isFinTrancheActuelle: (trancheActuelle && nbJours === trancheActuelle.tranche_fin),
            dateFin: d.toLocaleDateString('fr-FR')
        });
    });

    // 3. Identifier le meilleur choix (coût journalier le plus bas)
    const meilleurPrixParJour = Math.min(...resultats.map(r => r.prixParJour));

    resultats.forEach(r => {
        const isMeilleur = r.prixParJour === meilleurPrixParJour;
        let rowClass = '';
        let badge = '';

        if (r.isFinTrancheActuelle) {
            rowClass = 'table-warning';
            badge = '<span class="badge bg-warning text-dark ms-1">Fin de tranche actuelle</span>';
        } else if (isMeilleur) {
            rowClass = 'table-success fw-semibold';
            badge = '<span class="badge bg-success ms-1">✨ Meilleur rapport prix/jour</span>';
        }

        html += `
            <tr class="${rowClass}">
                <td>${new Date(date_debut).toLocaleDateString('fr-FR')}</td>
                <td>${r.dateFin}</td>
                <td>${r.nbJours} jours ${badge}</td>
                <td class="fw-semibold">${formatMoney(r.prixReduit)}</td>
            </tr>`;
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

const maskInput = document.getElementById('tarif_location_mask');
const realInput = document.getElementById('tarif_location');

if (maskInput) {
    maskInput.addEventListener('input', function(e) {
        // 1. On ne garde que les chiffres
        let rawValue = this.value.replace(/\D/g, '');

        // 2. On met à jour le champ caché (pour le serveur et vos calculs JS)
        realInput.value = rawValue;

        // 3. On formate l'affichage du masque
        if (rawValue) {
            this.value = new Intl.NumberFormat('fr-FR').format(rawValue);
        } else {
            this.value = '';
        }

        // 4. On relance les calculs de simulation
        if (typeof calculer === "function") {
            calculer();
            calculerSimulation();
        }
    });
}

// Mise à jour lors du changement de matériel (Select)
dispositifSelect?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const nouveauTarifMin = parseFloat(selectedOption.getAttribute('data-tarif')) || 0;

    currentTarifMin = nouveauTarifMin;

    if(maskInput) {
        maskInput.value = new Intl.NumberFormat('fr-FR').format(nouveauTarifMin);
        realInput.value = nouveauTarifMin;
    }

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

/* =================================
   Gestion du Modal de Confirmation
================================= */
document.getElementById('publicationForm').addEventListener('submit', function(e) {
    // Si c'est un mode édition, on laisse passer sans modal ou on adapte
    if ("{{ $isEdit }}" == "1") return;

    e.preventDefault(); // On bloque l'envoi direct

    // Remplir le résumé dans le modal
    document.getElementById('resume_jours').innerText = document.getElementById('nb_jours').value + " jours";
    document.getElementById('resume_prix').innerText = document.getElementById('prix_publication').value;
    document.getElementById('resume_bonus').innerText = "-" + document.getElementById('bonus_accorde').value;
    document.getElementById('resume_cout').innerText = document.getElementById('cout_publication').value;

    const myModal = new bootstrap.Modal(document.getElementById('confirmPubModal'));
    myModal.show();
});

// Action finale du bouton dans le modal
document.getElementById('confirmFinalBtn').addEventListener('click', function() {
    document.getElementById('publicationForm').submit();
});

</script>
@endsection
