@php
    $isEdit = isset($dispositif);
@endphp

{{-- Affichage erreurs --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $isEdit ? route('user.dispositifs.update', $dispositif) : route('user.dispositifs.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- Catégorie & Type --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
            <select id="categorie_id" 
                    name="categorie_id"
                    data-child="types_dispositif_id"
                    data-url="{{ url('types_dispositif/by-categorie') }}/"
                    class="form-select" required>
                <option value="">Sélectionner</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('categorie_id', $dispositif->type_dispositif->categorie_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Type de dispositif <span class="text-danger">*</span></label>
            <select id="types_dispositif_id"
                    name="types_dispositif_id"
                    data-selected="{{ old('types_dispositif_id', $dispositif->types_dispositif_id ?? '') }}"
                    class="form-select"
                    required>
                <option value="">Sélectionner</option>
            </select>
        </div>
    </div>

    {{-- Désignation & Immatriculation --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Désignation (Marque & modèle) <span class="text-danger">*</span></label>
            <input type="text" name="designation" class="form-control"
                   value="{{ old('designation', $dispositif->designation ?? '') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">N° d'immatriculation</label>
            <input type="text" name="numero_immatriculation" class="form-control"
                   value="{{ old('numero_immatriculation', $dispositif->numero_immatriculation ?? '') }}">
        </div>
    </div>

    {{-- Paramètres dynamiques --}}
    <div id="params-container" class="mt-3"></div>

    {{-- Etat & Statut --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Etat <span class="text-danger">*</span></label>
            <select name="etat" class="form-select" required>
                <option value="">Sélectionner</option>
                @foreach(['Neuf','Bon','Révisé'] as $etat)
                    <option value="{{ $etat }}"
                        {{ old('etat', $dispositif->etat ?? '') == $etat ? 'selected' : '' }}>
                        {{ $etat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select name="statut" class="form-select" required>
                <option value="">Sélectionner</option>
                @foreach(['Actif','Inactif','Suspendu'] as $statut)
                    <option value="{{ $statut }}"
                        {{ strtolower(old('statut', $dispositif->statut ?? '')) == strtolower($statut) ? 'selected' : '' }}>
                        {{ $statut }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Photos --}}
    <div class="mb-3">
        <label class="form-label">Photos</label>
        <input type="file" name="photos[]" multiple class="form-control">
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">
            {{ $isEdit ? 'Enregistrer' : 'Créer' }}
        </button>
        <a href="{{ route('user.dispositifs.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>

{{-- Photos édition --}}
@if($isEdit && $dispositif->photos->count())
<div class="row mt-3">
    @foreach($dispositif->photos as $photo)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <img src="{{ asset('storage/'.$photo->path) }}" class="card-img-top" style="height:120px; object-fit:cover;">
            </div>
        </div>
    @endforeach
</div>
@endif

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="{{ asset('js/dependent-select.js') }}"></script>

<script>
$(document).ready(function(){

    const container = $('#params-container');

    const baseUrl = "{{ url('/') }}";

    const existingParams = {!! json_encode(
        isset($dispositif) ? $dispositif->params->pluck('value','type_dispositif_param_id') : []
    ) !!};

    async function loadParams(typeId){

        container.html('');
        if(!typeId) return;

        try {            
            
            const res = await fetch(`${baseUrl}/types_dispositif/${typeId}/params`);
            const params = await res.json();

            params.forEach(param => {

                const paramId = param.id ?? null;

                if(!paramId){
                    console.error("Paramètre sans ID :", param);
                    return;
                }
                
                const inputName = `params[${paramId}]`;
                const labelText = param.label ?? param.name;
                const required  = param.required ?? false;
                const value     = existingParams[paramId] ?? '';
                const inputId   = `param_${paramId}`;

                const wrapper = $('<div class="mb-3"></div>');

                let unit = param.numeric_value_unit ? ` (${param.numeric_value_unit})` : '';
                let star = required ? ' <span class="text-danger">*</span>' : '';

                const labelEl = $(`
                    <label class="form-label" for="${inputId}">
                        ${labelText}${unit}${star}
                    </label>
                `);

                let input;

                // SELECT
                if(param.list_values){

                    input = $('<select class="form-select"></select>').attr({
                        name: inputName,
                        id: inputId
                    });

                    input.append('<option value="">Sélectionner</option>');

                    param.list_values.split(',').forEach(opt=>{
                        opt = opt.trim();
                        const option = $('<option></option>').val(opt).text(opt);
                        if(opt == value) option.prop('selected', true);
                        input.append(option);
                    });

                }
                // INPUT NORMAL
                else {

                    let type = 'text';
                    if(param.value_type === 'int' || param.value_type === 'decimal') type='number';
                    if(param.value_type === 'date') type='date';

                    input = $('<input>', {
                        type: type,
                        class: 'form-control',
                        id: inputId,
                        name: inputName,
                        value: value
                    });

                    if(param.value_type === 'decimal'){
                        input.attr('step','0.01');
                    }
                }

                if(required){
                    input.prop('required', true);
                }

                wrapper.append(labelEl).append(input);
                container.append(wrapper);
            });

        } catch(error){
            console.error("Erreur chargement paramètres :", error);
            container.html('<div class="alert alert-danger">Erreur chargement paramètres</div>');
        }
    }

    // Sélection type → afficher params
    const typeSelect = $('#types_dispositif_id');

    typeSelect.on('change', function(){
        loadParams($(this).val());
    });

    // Préchargement en édition
    const selectedType = typeSelect.data('selected');
    if(selectedType){
        // Observer pour attendre que dependent-select.js ait rempli le select
        const observer = new MutationObserver(function(){
            if(typeSelect.find(`option[value="${selectedType}"]`).length){
                typeSelect.val(selectedType).trigger('change');
                observer.disconnect();
            }
        });
        observer.observe(typeSelect[0], { childList: true });
    }

});
</script>
@endsection