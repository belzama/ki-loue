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

<h5>Tarifs</h5>

<div id="tarifs-wrapper">
    @if(isset($pays))
        @foreach($pays->tarifs as $i => $tarif)
            @include('partials.tarif_row', ['index'=>$i, 'tarif'=>$tarif])
        @endforeach
    @else
        @include('partials.tarif_row', ['index'=>0])
    @endif
</div>

<button type="button" class="btn btn-primary btn-sm mt-2" onclick="addTarif()">
    + Ajouter un tarif
</button>

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


<hr>
<div id="form-errors" class="alert alert-danger d-none"></div>
<button type="submit" class="btn btn-success">Enregistrer</button>


<script>

document.addEventListener('DOMContentLoaded', function(){

let tarifIndex = {{ isset($pays) ? $pays->tarifs->count() : 1 }};
let modeIndex = {{ isset($pays) ? $pays->modePaiements->count() : 1 }};

// =============================
// Ajouter un tarif
// =============================

window.addTarif = function(){

    let wrapper = document.getElementById('tarifs-wrapper');

    let html = `
        <div class="row border p-2 mb-2 tarif-item">

            <div class="col-md-3">
                <input type="text"
                       name="tarifs[${tarifIndex}][designation]"
                       class="form-control"
                       placeholder="Désignation"
                       required>
            </div>

            <div class="col-md-3">
                <input type="number"
                       name="tarifs[${tarifIndex}][tranche_debut]"
                       class="form-control"
                       placeholder="Début">
            </div>

            <div class="col-md-2">
                <input type="number"
                       name="tarifs[${tarifIndex}][tranche_fin]"
                       class="form-control"
                       placeholder="Fin">
            </div>

            <div class="col-md-2">
                <input type="number"
                       name="tarifs[${tarifIndex}][tranche_valeur]"
                       class="form-control"
                       placeholder="Valeur">
            </div>

            <div class="col-md-1">
                <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        onclick="removeTarif(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    tarifIndex++;

}


// =============================
// Ajouter un mode paiement
// =============================

window.addMode = function(){

    let wrapper = document.getElementById('mode_paiements-wrapper');

    let html = `
        <div class="row border p-2 mb-2 mode-item">

            <div class="col-md-3">
                <input type="text"
                       name="mode_paiements[${modeIndex}][designation]"
                       class="form-control"
                       placeholder="Désignation"
                       required>
            </div>

            <div class="col-md-3">
                <select name="mode_paiements[${modeIndex}][type]"
                        class="form-select"
                        required>

                    <option value="">Sélectionner</option>
                    <option value="Mobile Money">Mobile Money</option>
                    <option value="Visa Card">Visa Card</option>
                    <option value="Wallet">Wallet</option>
                    <option value="Espèce">Espèce</option>
                    <option value="Chèque">Chèque</option>

                </select>
            </div>

            <div class="col-md-3">
                <input type="text"
                       name="mode_paiements[${modeIndex}][api_url]"
                       class="form-control"
                       placeholder="URL API">
            </div>

            <div class="col-md-2">
                <input type="text"
                       name="mode_paiements[${modeIndex}][numero_compte]"
                       class="form-control"
                       placeholder="Numéro compte">
            </div>

            <div class="col-md-1">
                <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        onclick="removeMode(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    modeIndex++;

}


// =============================
// Supprimer tarif
// =============================

window.removeTarif = function(btn){
    btn.closest('.tarif-item').remove();
}


// =============================
// Supprimer mode
// =============================

window.removeMode = function(btn){
    btn.closest('.mode-item').remove();
}


// =============================
// Vérifier chevauchement tranches
// =============================

function checkTarifOverlap(){

    let ranges = [];
    let messages = [];
    let valid = true;

    document.querySelectorAll('.tarif-item').forEach((row, idx)=>{

        let debut = row.querySelector('input[name*="[tranche_debut]"]');
        let fin = row.querySelector('input[name*="[tranche_fin]"]');

        if(!debut.value || !fin.value) return;

        let start = Number(debut.value);
        let end = Number(fin.value);

        ranges.push({
            index: idx,
            start: start,
            end: end,
            debutInput: debut,
            finInput: fin
        });

    });

    for(let i=0;i<ranges.length;i++){

        for(let j=i+1;j<ranges.length;j++){

            let r1 = ranges[i];
            let r2 = ranges[j];

            if(r1.start <= r2.end && r2.start <= r1.end){

                valid=false;

                r1.debutInput.classList.add('is-invalid');
                r1.finInput.classList.add('is-invalid');

                r2.debutInput.classList.add('is-invalid');
                r2.finInput.classList.add('is-invalid');

                messages.push(`Les tranches ${r1.index+1} et ${r2.index+1} se chevauchent`);

            }

        }

    }

    return {valid,messages};

}


// =============================
// Validation formulaire
// =============================

const form = document.querySelector('form');

if(!form) return;

form.addEventListener('submit', function(e){

    let valid = true;
    let messages = [];

    document.querySelectorAll('.is-invalid')
        .forEach(el=>el.classList.remove('is-invalid'));

    const errorBox = document.getElementById('form-errors');

    errorBox.classList.add('d-none');
    errorBox.innerHTML = '';


// ================= TARIFS =================

document.querySelectorAll('.tarif-item').forEach((row, idx)=>{

    const designation = row.querySelector('input[name*="[designation]"]');
    const debut = row.querySelector('input[name*="[tranche_debut]"]');
    const fin = row.querySelector('input[name*="[tranche_fin]"]');
    const valeur = row.querySelector('input[name*="[tranche_valeur]"]');

    if(!designation.value.trim()){
        valid=false;
        designation.classList.add('is-invalid');
        messages.push(`Tarif ${idx+1} : désignation obligatoire`);
    }

    if(!debut.value.trim()){
        valid=false;
        debut.classList.add('is-invalid');
        messages.push(`Tarif ${idx+1} : début obligatoire`);
    }

    if(!fin.value.trim()){
        valid=false;
        fin.classList.add('is-invalid');
        messages.push(`Tarif ${idx+1} : fin obligatoire`);
    }

    if(!valeur.value.trim()){
        valid=false;
        valeur.classList.add('is-invalid');
        messages.push(`Tarif ${idx+1} : valeur obligatoire`);
    }

    if(debut.value && fin.value && Number(debut.value) > Number(fin.value)){
        valid=false;
        debut.classList.add('is-invalid');
        fin.classList.add('is-invalid');
        messages.push(`Tarif ${idx+1} : début > fin`);
    }

});


// ================= CHEVAUCHEMENT =================

const overlapCheck = checkTarifOverlap();

if(!overlapCheck.valid){

    valid=false;

    messages = messages.concat(overlapCheck.messages);

}


// ================= MODES =================

document.querySelectorAll('.mode-item').forEach((row, idx)=>{

    const designation = row.querySelector('input[name*="[designation]"]');
    const type = row.querySelector('select[name*="[type]"]');

    if(!designation.value.trim()){
        valid=false;
        designation.classList.add('is-invalid');
        messages.push(`Mode ${idx+1} : désignation obligatoire`);
    }

    if(!type.value.trim()){
        valid=false;
        type.classList.add('is-invalid');
        messages.push(`Mode ${idx+1} : type obligatoire`);
    }

});


// ================= ERREURS =================

if(!valid){

    e.preventDefault();

    errorBox.innerHTML = `
        <strong>Veuillez corriger les erreurs :</strong>
        <ul>${messages.map(m=>`<li>${m}</li>`).join('')}</ul>
    `;

    errorBox.classList.remove('d-none');

    window.scrollTo({top:0,behavior:'smooth'});

}

});

});

</script>