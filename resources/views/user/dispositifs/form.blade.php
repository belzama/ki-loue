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

<div id="global-errors" class="alert alert-danger" style="display:none;">
    <ul class="mb-0" id="global-errors-list"></ul>
</div>

<form id="dispositifForm"
      action="{{ $isEdit ? route('user.dispositifs.update', $dispositif) : route('user.dispositifs.store') }}"
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    {{-- Catégorie & Type --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Catégorie de matériel <span class="text-danger">*</span></label>
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
            <div class="invalid-feedback" id="error-categorie_id"></div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Type de matériel <span class="text-danger">*</span></label>
            <select id="types_dispositif_id"
                    name="types_dispositif_id"
                    data-selected="{{ old('types_dispositif_id', $dispositif->types_dispositif_id ?? '') }}"
                    class="form-select"
                    >
                <option value="">Sélectionner</option>
            </select>
            <div class="invalid-feedback" id="error-types_dispositif_id"></div>
        </div>
    </div>

    {{-- Marque & modèle --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label">Marque</label>
            <input type="text" id="marque" name="marque" class="form-control"
                   value="{{ old('marque', $dispositif->marque ?? '') }}">  
            <div class="invalid-feedback" id="error-marque"></div>  
        </div>

        <div class="col-md-6">
            <label class="form-label">Modèle</label>
            <input type="text" id="modele" name="modele" class="form-control"
                   value="{{ old('modele', $dispositif->modele ?? '') }}">
            <div class="invalid-feedback" id="error-modele"></div>
        </div>
    </div>

    {{-- Paramètres dynamiques --}}
    <div id="params-container" class="mt-3"></div>

    {{-- Etat --}}
    <div class="mb-3">
        <label class="form-label">Etat <span class="text-danger">*</span></label>
        <div id="etat" class="d-flex gap-4">
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
        <div class="invalid-feedback" id="error-etat"></div>
    </div>

    {{-- Photos --}}
    <div class="mb-3">
        <label class="form-label">Photos</label>
        <div id="photos-container" class="row g-3"></div>
        <div class="invalid-feedback" id="error-photos-container"></div>
    </div>

    <div id="progressContainer" style="display:none; margin-bottom: 20px;">
        <div class="progress" style="height: 25px;">
            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                 role="progressbar" style="width: 0%;">0%</div>
        </div>
        <small id="progressStatus" class="text-muted text-center d-block">Téléchargement en cours...</small>
    </div>

    <div class="mt-3">
        <button type="submit" id="submitBtn" class="btn btn-success">
            {{ $isEdit ? 'Enregistrer les modifications' : 'Créer le dispositif' }}
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

        const allowedTypes = ['image/jpeg','image/png','image/jpg'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        // supprimer ancienne erreur
        $(`#error_photo_${index}`).remove();

        // type
        if(!allowedTypes.includes(file.type)){
            showPhotoError(index,"Format invalide. Utilisez JPG ou PNG.");
            event.target.value='';
            return;
        }

        // taille
        if(file.size > maxSize){
            showPhotoError(index,"La photo dépasse 5MB.");
            event.target.value='';
            return;
        }

        // vérifier que c'est réellement une image
        const img = new Image();

        img.onload = function(){

            const reader = new FileReader();
            reader.onload = function(e){

                const image = document.createElement('img');
                image.src = e.target.result;
                image.className = 'photo-preview';
                image.id = 'preview_'+index;

                const oldEl = document.getElementById('preview_'+index);
                if(oldEl) oldEl.replaceWith(image);

            };

            reader.readAsDataURL(file);
        };

        img.onerror = function(){
            showPhotoError(index,"Le fichier n'est pas une image valide.");
            event.target.value='';
        };

        img.src = URL.createObjectURL(file);
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

                //if(required) input.prop('required', true);
                wrapper.append(labelEl).append(input);
                container.append(wrapper);
            });

        } catch(error){
            console.error("Erreur chargement paramètres :", error);
            container.html('<div class="alert alert-danger">Erreur chargement paramètres</div>');
        }
    }

    function showPhotoError(index,message){

        const errorHtml = `
            <div id="error_photo_${index}" class="text-danger small mt-1">
                ${message}
            </div>
        `;

        $(`#preview_${index}`).closest('.photo-box').append(errorHtml);
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

document.getElementById('dispositifForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);
    let xhr = new XMLHttpRequest();
    let btn = document.getElementById('submitBtn');
    let progressContainer = document.getElementById('progressContainer');
    let progressBar = document.getElementById('progressBar');
    let progressStatus = document.getElementById('progressStatus');

    // Affichage de l'interface de progression
    progressContainer.style.display = 'block';
    btn.disabled = true;

    xhr.open('POST', form.action, true);
    
    // Crucial pour que Laravel reconnaisse la requête AJAX et retourne du JSON
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.upload.addEventListener('progress', function(e) {
        if (e.lengthComputable) {
            let percentComplete = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percentComplete + '%';
            progressBar.innerText = percentComplete + '%';
            
            if(percentComplete === 100) {
                progressStatus.innerText = "Finalisation et enregistrement sur le serveur...";
                progressBar.classList.replace('bg-success', 'bg-info');
            }
        }
    });

    xhr.onload = function() {
        let btn = document.getElementById('submitBtn');
        let progressContainer = document.getElementById('progressContainer');
        
        if (xhr.status >= 200 && xhr.status < 300) {
            window.location.href = "{{ route('user.dispositifs.index') }}";
        } else {
            btn.disabled = false;
            progressContainer.style.display = 'none';

            // 1. Nettoyer les anciennes erreurs
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
            let globalErrors = document.getElementById('global-errors');
            let globalList = document.getElementById('global-errors-list');
            globalErrors.style.display = 'none';
            globalList.innerHTML = '';

            try {
                let response = JSON.parse(xhr.responseText);

                if (xhr.status === 422) { // Erreurs de validation Laravel
                    let errors = response.errors;
                    
                    globalErrors.style.display = 'block';

                    for (let field in errors) {
                        // Ajouter à la liste globale en haut
                        let li = document.createElement('li');
                        li.innerText = errors[field][0];
                        globalList.appendChild(li);

                        // Cibler le champ spécifique (ex: marque, modele)
                        // Note : pour les photos.*, on cible l'ID photos-container
                        let fieldId = field.replace('.', '_'); 
                        let input = document.getElementById(field) || document.getElementsByName(field)[0];
                        let errorFeedback = document.getElementById('error-' + field);

                        if (input) {
                            input.classList.add('is-invalid');
                        }
                        if (errorFeedback) {
                            errorFeedback.innerText = errors[field][0];
                            errorFeedback.style.display = 'block';
                        }
                    }
                    // Scroller vers le haut pour voir les erreurs
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                } else {
                    alert("Erreur système : " + (response.message || "Erreur inconnue"));
                }
            } catch (e) {
                console.error("Erreur brute :", xhr.responseText);
                alert("Le serveur a renvoyé une erreur critique (voir console).");
            }
        }
    };

    xhr.onerror = function() {
        alert("Erreur réseau ou connexion interrompue.");
        btn.disabled = false;
        progressContainer.style.display = 'none';
    };

    xhr.send(formData);
});
</script>
@endsection