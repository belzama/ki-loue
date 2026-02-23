@csrf

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Catégorie <span class="text-danger">*</span></label>
        <select name="categorie_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" 
                    {{ (old('categorie_id', $types_dispositif->categorie_id ?? '') == $cat->id) ? 'selected' : '' }}>
                    {{ $cat->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label>Nom du type <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" 
            value="{{ old('nom', $types_dispositif->nom ?? '') }}" required>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Tarif minimum <span class="text-danger">*</span></label>
        <input type="number" name="tarif_min" class="form-control" step="0.01"
            value="{{ old('tarif_min', $types_dispositif->tarif_min ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label>Tarif maximum <span class="text-danger">*</span></label>
        <input type="number" name="tarif_max" class="form-control" step="0.01"
            value="{{ old('tarif_max', $types_dispositif->tarif_max ?? '') }}" required>
    </div>
</div>

<hr>

<h5>Paramètres du type</h5>

<div id="params-wrapper">
    @if(isset($types_dispositif))
        @foreach($types_dispositif->params as $i => $param)
            @include('partials.type_dispositif_param_row', ['index'=>$i, 'param'=>$param])
        @endforeach
    @else
        @include('partials.type_dispositif_param_row', ['index'=>0])
    @endif
</div>

<button type="button" class="btn btn-primary btn-sm mt-2" onclick="addParam()">
    + Ajouter un paramètre
</button>


<button type="submit" class="btn btn-success">Enregistrer</button>



<script>
let paramIndex = {{ isset($types_dispositif) ? $types_dispositif->params->count() : 1 }};

function addParam() {

    let wrapper = document.getElementById('params-wrapper');

    let html = `
        <div class="row border p-2 mb-2 param-item">

            <div class="col-md-2">
                <input type="text" name="params[${paramIndex}][name]"
                       class="form-control"
                       placeholder="Nom">
            </div>

            <div class="col-md-3">
                <input type="text" name="params[${paramIndex}][label]"
                       class="form-control"
                       placeholder="Libellé">
            </div>

            <div class="col-md-2">
                <select name="params[${paramIndex}][value_type]" class="form-select">
                    <option value="string">Texte</option>
                    <option value="int">Entier</option>
                    <option value="decimal">Décimal</option>
                    <option value="date">Date</option>
                    <option value="datetime">Date & Heure</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="params[${paramIndex}][list_values]"
                       class="form-control"
                       placeholder="Valeur1,Valeur2,Valeur3,...">
            </div>

            <div class="col-md-1 d-flex align-items-center justify-content-center">
                <input type="hidden"
                    name="params[${paramIndex}][required]"
                    value="0">
                    
                <input type="checkbox"
                       name="params[${paramIndex}][required]"
                       value="1" 
                       class="form-check-input me-2"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Obligatoire">
                <label class="form-check-label"></label>
            </div>

            <div class="col-md-1">
                <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        onclick="removeParam(this)"
                        title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    paramIndex++;
}

function removeParam(button) {
    button.closest('.param-item').remove();
}

document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>