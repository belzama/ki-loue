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
                    class="form-select">
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
        <div id="photos-container" name="photos" class="row g-3"></div>
        <div class="invalid-feedback" id="error-photos"></div>
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
$(document).ready(function() {
    // Utilisation du sélecteur natif pour les événements personnalisés
    const typeSelectEl = document.getElementById('types_dispositif_id');
    const container = $('#params-container');
    const photosContainer = $('#photos-container');
    const baseUrl = "{{ url('/') }}";

    // Récupération sécurisée des données existantes (Blade)
    const existingParams = {!! json_encode(isset($dispositif) ? $dispositif->params->pluck('value','type_dispositif_param_id') : []) !!};
    const existingPhotos = {!! json_encode(isset($dispositif) ? $dispositif->photos->map(fn($p) => ['id'=>$p->id,'url'=>asset('storage/'.$p->path)])->values() : []) !!};
    
    // --- RENDU DES PHOTOS ---
    function renderPhotoInputs(maxPhotos) {
        photosContainer.empty();
        for (let i = 0; i < maxPhotos; i++) {
            const photo = existingPhotos[i] ?? null;
            const previewHtml = photo 
                ? `<img id="preview_${i}" class="photo-preview" src="${photo.url}">`
                : `<div id="preview_${i}" class="photo-empty"><i class="bi bi-image"></i></div>`;

            photosContainer.append(`
                <div class="col-md-3">
                    <div class="photo-box">
                        ${previewHtml}
                        <div class="photo-buttons">
                            <label class="btn btn-sm btn-primary mb-0">
                                <i class="bi bi-pencil"></i>
                                <input type="file" name="photos[${i}]" hidden accept="image/jpeg,image/png" onchange="previewPhoto(event,${i})">
                            </label>
                            ${i !== 0 ? `<button type="button" class="btn btn-sm btn-danger remove-photo" data-index="${i}"><i class="bi bi-trash"></i></button>` : ''}
                        </div>
                        ${photo ? `<input type="hidden" name="existing_photos[${i}]" value="${photo.id}">` : ''}
                    </div>
                </div>`);
        }
    }

    // --- CHARGEMENT DES PARAMÈTRES ---
    async function loadParams(typeId) {
        console.log("Exécution de loadParams pour ID :", typeId);
        
        if (!typeId) {
            container.empty();
            photosContainer.empty();
            return;
        }

        try {
            const res = await fetch(`${baseUrl}/types_dispositif/${typeId}/params`);
            const data = await res.json();

            // 1. Rendu des photos
            renderPhotoInputs(data.nb_max_photo ?? 4);

            // 2. Rendu des paramètres
            container.empty();
            if (data.params) {
                data.params.forEach(param => {
                    const value = existingParams[param.id] ?? ''; // On utilise 'value'
                    const inputName = `params[${param.id}]`;
                    const inputId = `param_${param.id}`;
                    
                    let inputHtml = '';
                    if (param.list_values) {
                        inputHtml = `<select class="form-select" name="${inputName}" id="${inputId}">
                            <option value="">Sélectionner</option>
                            ${param.list_values.split(',').map(v => {
                                let val = v.trim();
                                return `<option value="${val}" ${val == value ? 'selected' : ''}>${val}</option>`;
                            }).join('')}
                        </select>`;
                    } else {
                        let inputType = (['int', 'decimal'].includes(param.value_type)) ? 'number' : (param.value_type === 'date' ? 'date' : 'text');
                        inputHtml = `<input type="${inputType}" class="form-control" name="${inputName}" id="${inputId}" value="${value}" ${param.value_type === 'decimal' ? 'step="0.01"' : ''}>`;
                    }

                    container.append(`
                        <div class="mb-3">
                            <label class="form-label" for="${inputId}">${param.label || param.name} ${param.required ? '<span class="text-danger">*</span>' : ''}</label>
                            ${inputHtml}
                            <div class="invalid-feedback" id="error-params_${param.id}"></div>
                        </div>`);
                });
            }
        } catch (e) { 
            console.error("Erreur lors du chargement des paramètres :", e); 
        }
    }

    // --- ÉVÉNEMENTS ---
    if (typeSelectEl) {
        // Capturer l'événement du script AJAX
        typeSelectEl.addEventListener('select:ready', function(e) {
            console.log("Signal 'select:ready' reçu !");
            loadParams(e.detail.value);
        });

        // Capturer le changement manuel
        typeSelectEl.addEventListener('change', function() {
            loadParams(this.value);
        });

        // Init forcé si une valeur est déjà là (cas rare sans AJAX)
        if (typeSelectEl.value) {
            loadParams(typeSelectEl.value);
        }
    }
});

// Fonctions globales pour les photos
window.previewPhoto = function(event, index) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        $(`#preview_${index}`).replaceWith(`<img id="preview_${index}" class="photo-preview" src="${e.target.result}">`);
    };
    reader.readAsDataURL(file);
};

$(document).on('click', '.remove-photo', function() {
    const idx = $(this).data('index');
    $(`#preview_${idx}`).replaceWith(`<div id="preview_${idx}" class="photo-empty"><i class="bi bi-image"></i></div>`);
    $(`input[name="photos[${idx}]"]`).val('');
    $(`input[name="existing_photos[${idx}]"]`).remove();
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
                        // 1. Liste globale
                        let li = document.createElement('li');
                        li.innerText = errors[field][0];
                        globalList.appendChild(li);

                        if (field.startsWith('photos')) {
                            let photoGlobalError = document.getElementById('error-photos');
                            if (photoGlobalError) {
                                photoGlobalError.innerText = errors[field][0];
                                photoGlobalError.style.display = 'block';
                            }
                        }

                        // 2. Ciblage local (on remplace les points par des underscores pour matcher les IDs HTML)
                        let sanitizedField = field.replace(/\./g, '_'); 
                        
                        // On cherche l'input par son name ou son ID
                        let input = document.getElementsByName(field)[0] || document.getElementById(field);
                        
                        // On cherche la div d'erreur par l'ID conventionnel error-nom_du_champ
                        let errorFeedback = document.getElementById('error-' + sanitizedField) || 
                                            document.getElementById('error-' + field);

                        if (input) {
                            input.classList.add('is-invalid');
                            // Si c'est un paramètre dynamique, on peut aussi ajouter la classe à l'input même s'il n'a pas de div dédiée
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