<div class="row border p-2 mb-2 param-item">

    <div class="col-md-2">
        <input type="text"
               name="params[{{ $index }}][name]"
               class="form-control"
               placeholder="Nom"
               value="{{ $param->name ?? '' }}">
    </div>

    <div class="col-md-3">
        <input type="text"
               name="params[{{ $index }}][label]"
               class="form-control"
               placeholder="Libellé"
               value="{{ $param->label ?? '' }}">
    </div>    

    <div class="col-md-2">
        <select name="params[{{ $index }}][value_type]"
                class="form-select">
            <option value="string" {{ (isset($param) && $param->value_type=='string') ? 'selected' : '' }}>Texte</option>
            <option value="int" {{ (isset($param) && $param->value_type=='int') ? 'selected' : '' }}>Entier</option>
            <option value="decimal" {{ (isset($param) && $param->value_type=='decimal') ? 'selected' : '' }}>Décimal</option>
            <option value="date" {{ (isset($param) && $param->value_type=='date') ? 'selected' : '' }}>Date</option>
            <option value="datetime" {{ (isset($param) && $param->value_type=='datetime') ? 'selected' : '' }}>Date & Heure</option>
       </select>
    </div>

    <div class="col-md-3">
        <input type="text"
               name="params[{{ $index }}][list_values]"
               class="form-control"
               placeholder="Valeur1,Valeur2,Valeur3"
               value="{{ $param->list_values ?? '' }}">
    </div>

    <div class="col-md-1 d-flex align-items-center justify-content-center">
        <input type="hidden"
            name="params[{{ $index }}][required]"
            value="0">

        <input type="checkbox"
            name="params[{{ $index }}][required]"
            value="1" 
            class="form-check-input me-2"
            data-bs-toggle="tooltip"
            data-bs-placement="top"
            title="Obligatoire"
            id="required_{{ $index }}"
            {{ (isset($param) && $param->required) ? 'checked' : '' }}>

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
