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

    {{-- Marque & modèle --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Marque <span class="text-danger">*</span></label>
            <input type="text" name="marque" class="form-control"
                   value="{{ old('marque', $dispositif->marque ?? '') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Modèle</label>
            <input type="text" name="modele" class="form-control"
                   value="{{ old('modele', $dispositif->modele ?? '') }}">
        </div>
    </div>

    {{-- Paramètres dynamiques --}}
    <div id="params-container" class="mt-3"></div>

    {{-- Etat --}}
    <div class="mb-3">
        <label class="form-label">Etat <span class="text-danger">*</span></label>
        <div class="d-flex gap-4">
            @foreach(['Neuf','Bon','Révisé'] as $etat)
            <div class="form-check">
                <input class="form-check-input"
                       type="radio"
                       name="etat"
                       id="etat_{{ strtolower($etat) }}"
                       value="{{ $etat }}"
                       {{ old('etat',$dispositif->etat ?? 'Neuf') == $etat ? 'checked' : '' }}>
                <label class="form-check-label" for="etat_{{ strtolower($etat) }}">
                    {{ $etat }}
                </label>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Photos --}}
    <div class="mb-3">
        <label class="form-label">Photos</label>
        <div id="photos-container" class="row g-3"></div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">
            {{ $isEdit ? 'Enregistrer' : 'Créer' }}
        </button>
        <a href="{{ route('user.dispositifs.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="{{ asset('js/dependent-select.js') }}"></script>

<script>
$(document).ready(function(){

    const container = $('#params-container');
    const photosContainer = $('#photos-container');
    const baseUrl = "{{ url('/') }}";

    const existingParams = {!! json_encode(
        isset($dispositif) ? $dispositif->params->pluck('value','type_dispositif_param_id') : []
    ) !!};

    const existingPhotos = {!! json_encode(
        isset($dispositif)
            ? $dispositif->photos->map(function($p){
                return ['id'=>$p->id,'url'=>asset('storage/'.$p->path)];
            })->values()
            : []
    ) !!};

    // Rendu des cases photos
    function renderPhotoInputs(maxPhotos){
        photosContainer.html('');

        for(let i=0; i<maxPhotos; i++){
            const photo = existingPhotos[i] ?? null;
            const hiddenInput = photo ? `<input type="hidden" name="existing_photos[${i}]" value="${photo.id}">` : '';

            const previewHtml = photo
                ? `<img id="preview_${i}" class="photo-preview" src="${photo.url}">`
                : `<div id="preview_${i}" class="photo-empty">
                    <i class="bi bi-image"></i>
                </div>`;

            const html = `
            <div class="col-md-3">
                <div class="photo-box">
                    ${previewHtml}
                    <div class="photo-buttons">
                        <label class="btn btn-sm btn-primary mb-0">
                            <i class="bi bi-pencil"></i>
                            <input type="file" name="photos[${i}]" hidden accept="image/jpeg,image/png" onchange="previewPhoto(event,${i})">
                        </label>
                        ${i!==0 ? `<button type="button" class="btn btn-sm btn-danger remove-photo" data-index="${i}">
                                        <i class="bi bi-trash"></i>
                                    </button>` : ''}
                    </div>
                    ${hiddenInput}
                </div>
            </div>`;
            photosContainer.append(html);
        }
    }

    // Preview photo et mise à jour du hidden
    window.previewPhoto = function(event, index){
        const file = event.target.files[0];
        if(!file) return;

        const allowed = ['image/jpg','image/jpeg','image/png'];
        if(!allowed.includes(file.type)){
            alert("Format non autorisé. Utilisez JPG, JPEG, PNG.");
            event.target.value='';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e){
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'photo-preview';
            img.id = 'preview_'+index;

            const oldEl = document.getElementById('preview_'+index);
            if(oldEl) oldEl.replaceWith(img);
        };
        reader.readAsDataURL(file);
    }

    // Supprimer une photo
    $(document).on('click','.remove-photo',function(){
        const index = $(this).data('index');
        const emptyHtml = `<div id="preview_${index}" class="photo-empty d-flex justify-content-center align-items-center">
                               <span>Aucune photo</span>
                           </div>`;
        $('#preview_'+index).replaceWith(emptyHtml);
        $(`input[name="existing_photos[${index}]"]`).remove();
        $(`input[name="photos[${index}]"]`).val('');
    });

    // Chargement des paramètres dynamiques
    async function loadParams(typeId){
        container.html('');
        if(!typeId) return;

        try{
            const res = await fetch(`${baseUrl}/types_dispositif/${typeId}/params`);
            const data = await res.json();
            const params = data.params;

            // Nombre de photos selon le type
            const nbPhotos = data.nb_max_photo ?? {{ $dispositif->type_dispositif->nb_max_photo ?? 5 }};
            renderPhotoInputs(nbPhotos);

            // Affichage des params
            params.forEach(param => {
                const paramId = param.id ?? null;
                if(!paramId) return;

                const inputName = `params[${paramId}]`;
                const labelText = param.label ?? param.name;
                const required = param.required ?? false;
                const value = existingParams[paramId] ?? '';
                const inputId = `param_${paramId}`;

                const wrapper = $('<div class="mb-3"></div>');
                let unit = param.numeric_value_unit ? ` (${param.numeric_value_unit})` : '';
                let star = required ? ' <span class="text-danger">*</span>' : '';
                const labelEl = $(`<label class="form-label" for="${inputId}">${labelText}${unit}${star}</label>`);

                let input;
                if(param.list_values){
                    input = $('<select class="form-select"></select>').attr({name:inputName,id:inputId});
                    input.append('<option value="">Sélectionner</option>');
                    param.list_values.split(',').forEach(opt=>{
                        opt = opt.trim();
                        const option = $('<option>').val(opt).text(opt);
                        if(opt == value) option.prop('selected', true);
                        input.append(option);
                    });
                } else {
                    let type = 'text';
                    if(param.value_type==='int'||param.value_type==='decimal') type='number';
                    if(param.value_type==='date') type='date';
                    input = $('<input>', {type:type,class:'form-control',id:inputId,name:inputName,value:value});
                    if(param.value_type==='decimal') input.attr('step','0.01');
                }

                if(required) input.prop('required', true);
                wrapper.append(labelEl).append(input);
                container.append(wrapper);
            });

        } catch(error){
            console.error("Erreur chargement paramètres :", error);
            container.html('<div class="alert alert-danger">Erreur chargement paramètres</div>');
        }
    }

    // Gestion du select type → params
    const typeSelect = $('#types_dispositif_id');
    typeSelect.on('change', function(){ loadParams($(this).val()); });

    // Préchargement édition
    const selectedType = typeSelect.data('selected');
    if(selectedType){
        const observer = new MutationObserver(function(){
            if(typeSelect.find(`option[value="${selectedType}"]`).length){
                typeSelect.val(selectedType).trigger('change');
                observer.disconnect();
            }
        });
        observer.observe(typeSelect[0],{childList:true});
    }

});
</script>
@endsection