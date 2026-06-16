@php
    $isEdit = isset($dispositif);
@endphp

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

    {{-- CATEGORIE / TYPE --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
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
            <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
            <select id="types_dispositif_id"
                    name="types_dispositif_id"
                    data-selected="{{ old('types_dispositif_id', $dispositif->types_dispositif_id ?? '') }}"
                    class="form-select">
                <option value="">Sélectionner</option>
            </select>
            <div class="invalid-feedback" id="error-types_dispositif_id"></div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label>Tarif minimum <span class="text-danger">*</span></label>
            <input type="number" id="tarif_min" name="tarif_min" class="form-control" step="0.01"
                value="{{ old('tarif_min', $dispositif->tarif_min ?? '') }}" required>
            <small class="form-text text-muted" id="tarif_min_hint"></small>
            <div class="invalid-feedback" id="error-tarif_min"></div>
        </div>

        <div class="col-md-6">
            <label>Tarif maximum <span class="text-danger">*</span></label>
            <input type="number" id="tarif_max" name="tarif_max" class="form-control" step="0.01"
                value="{{ old('tarif_max', $dispositif->tarif_max ?? '') }}" required>
            <small class="form-text text-muted" id="tarif_max_hint"></small>
            <div class="invalid-feedback" id="error-tarif_max"></div>
        </div>
    </div>

    {{-- MARQUE / MODELE --}}
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Marque</label>
            <input type="text" id="marque" name="marque" class="form-control"
                   value="{{ old('marque', $dispositif->marque ?? '') }}">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Modèle</label>
            <input type="text" id="modele" name="modele" class="form-control"
                   value="{{ old('modele', $dispositif->modele ?? '') }}">
        </div>
    </div>

    {{-- PARAMETRES DYNAMIQUES --}}
    <div id="params-container" class="mt-3"></div>

    {{-- PHOTOS --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Photos</label>
        <div id="photos-container" class="row g-3"></div>
    </div>

    {{-- PROGRESS --}}
    <div id="progressContainer" style="display:none;">
        <div class="progress" style="height:25px;">
            <div id="progressBar" class="progress-bar bg-success">0%</div>
        </div>
    </div>

    <button type="submit" id="submitBtn" class="btn btn-success">
        {{ $isEdit ? 'Modifier' : 'Créer' }}
    </button>

</form>

@include('user.dispositifs.confirm_modal')

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="{{ asset('js/dependent-select.js') }}"></script>

<script>
    $(document).ready(function() {
        // --- VARIABLES GLOBALES ---
        const typeSelectEl = document.getElementById('types_dispositif_id');
        const catSelectEl = document.getElementById('categorie_id');
        const container = $('#params-container');
        const photosContainer = $('#photos-container');
        const baseUrl = "{{ url('/') }}";

        // Données existantes (Blade)
        const existingParams = {!! json_encode(
            isset($dispositif)
                ? $dispositif->params->mapWithKeys(function ($p) {
                    return [$p->type_dispositif_param_id => [
                        'value' => $p->value,
                        'unit' => $p->typeDispositifParam->numeric_value_unit ?? null
                    ]];
                })
                : []
        ) !!};

        const existingPhotos = {!! json_encode(
            isset($dispositif)
            ? $dispositif->photos->map(fn($p) => [
                'id'=>$p->id,
                'url'=>asset('storage/'.$p->path)
                ])->values() : []
        ) !!};

        // --- 1. RENDU DES PHOTOS ---
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

        // --- 2. CHARGEMENT DYNAMIQUE (API) ---
        function setTarifLimits(min, max) {
            const tarifMinEl = document.getElementById('tarif_min');
            const tarifMaxEl = document.getElementById('tarif_max');
            const tarifMinHint = document.getElementById('tarif_min_hint');
            const tarifMaxHint = document.getElementById('tarif_max_hint');

            if (min == null || max == null) {
                tarifMinEl.removeAttribute('min');
                tarifMinEl.removeAttribute('max');
                tarifMaxEl.removeAttribute('min');
                tarifMaxEl.removeAttribute('max');
                tarifMinHint.textContent = '';
                tarifMaxHint.textContent = '';
                return;
            }

            min = parseFloat(min);
            max = parseFloat(max);

            tarifMinEl.min = min;
            tarifMinEl.max = max;
            tarifMaxEl.min = min;
            tarifMaxEl.max = max;

            tarifMinHint.textContent = `Doit être compris entre ${min} et ${max}`;
            tarifMaxHint.textContent = `Doit être compris entre ${min} et ${max}`;

            // Toujours mettre à jour les valeurs lors d'un changement de type
            tarifMinEl.value = min;
            tarifMaxEl.value = max;

            // Validation live (une seule fois pour éviter les doublons d'écouteurs)
            if (!tarifMinEl.dataset.listenerAttached) {
                tarifMinEl.addEventListener('input', () => {
                    const m = parseFloat(tarifMinEl.dataset.currentMin);
                    const M = parseFloat(tarifMinEl.dataset.currentMax);
                    validateTarifInput(tarifMinEl, m, M);
                });
                tarifMinEl.dataset.listenerAttached = 'true';
            }
            if (!tarifMaxEl.dataset.listenerAttached) {
                tarifMaxEl.addEventListener('input', () => {
                    const m = parseFloat(tarifMaxEl.dataset.currentMin);
                    const M = parseFloat(tarifMaxEl.dataset.currentMax);
                    validateTarifInput(tarifMaxEl, m, M);
                });
                tarifMaxEl.dataset.listenerAttached = 'true';
            }

            // Stocker les bornes actuelles pour les listeners
            tarifMinEl.dataset.currentMin = min;
            tarifMinEl.dataset.currentMax = max;
            tarifMaxEl.dataset.currentMin = min;
            tarifMaxEl.dataset.currentMax = max;
        }

        function validateTarifInput(el, min, max) {
            let val = parseFloat(el.value);
            if (isNaN(val)) return;

            if (val < min) {
                el.value = min;
            } else if (val > max) {
                el.value = max;
            }

            // Cohérence tarif_min <= tarif_max
            const tarifMinEl = document.getElementById('tarif_min');
            const tarifMaxEl = document.getElementById('tarif_max');
            let vMin = parseFloat(tarifMinEl.value);
            let vMax = parseFloat(tarifMaxEl.value);

            if (!isNaN(vMin) && !isNaN(vMax) && vMin > vMax) {
                if (el === tarifMinEl) {
                    tarifMaxEl.value = vMin;
                } else {
                    tarifMinEl.value = vMax;
                }
            }
        }

        async function loadParams(typeId) {

            if (!typeId) {
                container.empty();
                return;
            }

            container.html(`<div>Chargement...</div>`);

            try {
                const res = await fetch(`${baseUrl}/types_dispositif/${typeId}/params`);
                const data = await res.json();
                //console.log('tarif_min:', data.tarif_min, 'tarif_max:', data.tarif_max);

                renderPhotoInputs(data.nb_max_photo ?? 4);
                
                // --- GESTION TARIF MIN/MAX ---
                setTarifLimits(data.tarif_min, data.tarif_max);

                container.empty();

                data.params.forEach(param => {

                    const paramData = existingParams[param.id] ?? {};
                    const value = paramData.value ?? '';
                    const unit = param.numeric_value_unit ?? null;

                    const inputId = `param_${param.id}`;
                    const isNumeric = ['int','decimal'].includes(param.value_type);

                    let inputName = `params[${param.id}][value]`;
                    let inputHtml = '';

                    if (param.list_values) {

                        inputHtml = `
                    <select name="${inputName}" class="form-select">
                        <option value="">Sélectionner</option>
                        ${param.list_values.split(',').map(v =>
                            `<option value="${v.trim()}" ${v.trim()==value?'selected':''}>${v.trim()}</option>`
                        ).join('')}
                    </select>`;

                    } else {

                        let type = isNumeric ? 'number' :
                            param.value_type === 'date' ? 'date' :
                                param.value_type === 'datetime' ? 'datetime-local' : 'text';

                        inputHtml = `
                    <div class="input-group">
                        <input type="${type}"
                               class="form-control"
                               name="${inputName}"
                               value="${value}"
                               ${param.value_type==='decimal'?'step="0.01"':''}>
                        ${unit ? `<span class="input-group-text">${unit}</span>` : ''}
                    </div>

                    ${unit ? `<input type="hidden" name="params[${param.id}][unit]" value="${unit}">` : ''}`;
                    }

                    container.append(`
                <div class="col-md-6">
                    <label class="form-label">
                        ${param.label || param.name}
                        ${unit ? `(${unit})` : ''}
                    </label>
                    ${inputHtml}
                </div>`);
                });

            } catch (e) {
                container.html(`<div class="text-danger">Erreur chargement</div>`);
            }
        }

        // --- 3. GESTION DES ÉVÉNEMENTS & CASCADE ---
        if (typeSelectEl) {
            let isInitialLoadDone = false;

            // Signal envoyé par dependent-select.js
            typeSelectEl.addEventListener('select:ready', function(e) {
                const val = e.detail.value || typeSelectEl.value;
                if (val && !isInitialLoadDone) {
                    isInitialLoadDone = true;
                    loadParams(val);
                }
            });

            // Changement manuel
            typeSelectEl.addEventListener('change', function() {
                if (this.value) {
                    isInitialLoadDone = true;
                    loadParams(this.value);
                }
            });

            // Init forcée pour le mode Edit
            if (catSelectEl && catSelectEl.value) {
                setTimeout(() => {
                    if (!isInitialLoadDone && typeSelectEl.value) {
                        isInitialLoadDone = true;
                        loadParams(typeSelectEl.value);
                    }
                }, 800);
            }
        }

        // --- 4. RÉSUMÉ ET MODALE DE CONFIRMATION ---
        const dispositifForm = document.getElementById('dispositifForm');

        dispositifForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Vérification basique avant modale
            if (!typeSelectEl.value) {
                alert("Veuillez sélectionner un type de matériel.");
                return;
            }

            // Construction du résumé
            let summaryHtml = `
            <div class="col-md-6 mb-2"><strong>Catégorie :</strong><br> ${$("#categorie_id option:selected").text()}</div>
            <div class="col-md-6 mb-2"><strong>Type :</strong><br> ${$("#types_dispositif_id option:selected").text()}</div>
            <div class="col-md-6 mb-2"><strong>Marque :</strong><br> ${$("#marque").val() || 'N/A'}</div>
            <div class="col-md-6 mb-2"><strong>Modèle :</strong><br> ${$("#modele").val() || 'N/A'}</div>
            <div class="col-md-12 mb-2"><strong>État :</strong> <span class="badge bg-info text-dark">${$("input[name='etat']:checked").val()}</span></div>
            <div class="col-12"><hr><h6>Paramètres techniques :</h6><ul class="small">`;

            $("#params-container .mb-3").each(function() {
                const label = $(this).find('label').text().replace('*', '').trim();
                const input = $(this).find('input, select');
                let val = input.is('select') ? input.find('option:selected').text() : input.val();
                if (val && val !== "Sélectionner") {
                    const unit = $(this).find('.input-group-text').text();
                    summaryHtml += `<li><strong>${label} :</strong> ${val} ${unit}</li>`;
                }
            });
            summaryHtml += `</ul></div>`;

            $('#summaryContent').html(summaryHtml);
            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        });

        // Clic final dans la modale
        document.getElementById('finalSubmitBtn').addEventListener('click', function() {
            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            executeAjaxSubmit();
        });

        // --- 5. ENVOI AJAX FINAL ---
        function executeAjaxSubmit() {
            const formData = new FormData(dispositifForm);
            const xhr = new XMLHttpRequest();
            const btn = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');

            progressContainer.style.display = 'block';
            btn.disabled = true;

            xhr.open('POST', dispositifForm.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.addEventListener('progress', e => {
                if (e.lengthComputable) {
                    let percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressBar.innerText = percent + '%';
                }
            });

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    window.location.href = "{{ route('user.dispositifs.index') }}";
                } else {
                    btn.disabled = false;
                    progressContainer.style.display = 'none';
                    handleErrors(JSON.parse(xhr.responseText));
                }
            };
            xhr.send(formData);
        }

        function handleErrors(response) {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('').hide();
            $('#global-errors').show();
            const list = $('#global-errors-list').empty();

            if (response.errors) {
                for (let field in response.errors) {
                    list.append(`<li>${response.errors[field][0]}</li>`);
                    let sanitized = field.replace(/\./g, '_');
                    $(`[name="${field}"]`).addClass('is-invalid');
                    $(`#error-${sanitized}`).text(response.errors[field][0]).show();
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    });

    // --- FONCTIONS GLOBALES (Hors jQuery) ---
    window.previewPhoto = function(event, index) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
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
</script>
@endsection
