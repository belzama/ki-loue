
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

<div class="d-flex gap-3">

    {{-- Liste catégories à gauche --}}
    <div class="categorie-sidebar">
        <h6 class="categorie-sidebar-title">Catégorie</h6>
        {{-- Filtre catégories --}}
        <div class="mb-2">
            <input type="text"
                id="categorie-filter"
                placeholder="Rechercher..."
                class="form-control form-control-sm">
        </div>
        <ul class="categorie-sidebar-list" id="categorie-list">
            @foreach($categories as $cat)
                <li class="categorie-sidebar-item" 
                    data-id="{{ $cat->id }}"
                    data-name="{{ strtolower($cat->nom) }}">
                    <button type="button" 
                            class="categorie-sidebar-btn {{ old('categorie_id', $dispositif->type_dispositif->categorie_id ?? '') == $cat->id ? 'active' : '' }}"
                            onclick="selectCategorie({{ $cat->id }})">

                        @if($cat->image_link)
                            <img src="{{ asset('storage/'.$cat->image_link) }}" alt="{{ $cat->nom }}" class="categorie-sidebar-img">
                        @else
                            <span class="categorie-sidebar-placeholder"><i class="bi bi-grid"></i></span>
                        @endif

                        <span>{{ $cat->nom }}</span>
                    </button>
                </li>
            @endforeach
        </ul>

        {{-- Champ caché pour soumettre la valeur --}}
        <input type="hidden" id="categorie_id" name="categorie_id">
        <div class="invalid-feedback d-block" id="error-categorie_id"></div>
    </div>

    {{-- Formulaire à droite --}}
    <div class="flex-grow-1">

        <form id="dispositifForm"
              action="{{ $isEdit ? route('user.dispositifs.update', $dispositif) : route('user.dispositifs.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- TYPE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                <select id="types_dispositif_id"
                        name="types_dispositif_id"
                        data-selected="{{ old('types_dispositif_id', $dispositif->types_dispositif_id ?? '') }}"
                        class="form-select">
                    <option value="">Sélectionner</option>
                </select>
                <div class="invalid-feedback" id="error-types_dispositif_id"></div>
            </div>

            {{-- TARIFS --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label>Prix minimum de location <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" id="tarif_min_display" class="form-control" inputmode="numeric"
                            value="{{ old('tarif_min', $dispositif->tarif_min ?? '') }}">
                        <span class="input-group-text">
                            {{ auth()->user()->pays->devise->symbol ?? '' }} / JOUR
                        </span>
                    </div>
                    <input type="hidden" id="tarif_min" name="tarif_min"
                        value="{{ old('tarif_min', $dispositif->tarif_min ?? '') }}" required>
                    <div class="invalid-feedback" id="error-tarif_min"></div>
                </div>
                <div class="col-md-6">
                    <label>Prix maximum de location <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" id="tarif_max_display" class="form-control" inputmode="numeric"
                            value="{{ old('tarif_max', $dispositif->tarif_max ?? '') }}">
                        <span class="input-group-text">
                            {{ auth()->user()->pays->devise->symbol ?? '' }} / JOUR
                        </span>
                    </div>
                    <input type="hidden" id="tarif_max" name="tarif_max"
                        value="{{ old('tarif_max', $dispositif->tarif_max ?? '') }}" required>
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
    </div>
</div>

@include('user.dispositifs.confirm_modal')

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="{{ asset('js/dependent-select.js') }}"></script>

<script>
    document.getElementById('categorie-filter').addEventListener('input', function () {
        const search = this.value.toLowerCase().trim();
        document.querySelectorAll('.categorie-sidebar-item').forEach(item => {
            item.style.display = item.dataset.name.includes(search) ? '' : 'none';
        });
    });

    $(document).ready(function() {
        // --- VARIABLES GLOBALES ---
        const typeSelectEl = document.getElementById('types_dispositif_id');
        const container = $('#params-container');
        const photosContainer = $('#photos-container');
        const baseUrl = "{{ url('/') }}";

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
                    'id'  => $p->id,
                    'url' => asset('storage/' . $p->path)
                ])->values()
                : []
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

        // --- 2. TARIFS ---
        function setTarifLimits(min, max) {
            const tarifMinEl  = document.getElementById('tarif_min');
            const tarifMaxEl  = document.getElementById('tarif_max');
            const tarifMinDisplay = document.getElementById('tarif_min_display');
            const tarifMaxDisplay = document.getElementById('tarif_max_display');
            const tarifMinHint = document.getElementById('tarif_min_hint');
            const tarifMaxHint = document.getElementById('tarif_max_hint');

            if (min == null || max == null) {
                if (tarifMinHint) tarifMinHint.textContent = '';
                if (tarifMaxHint) tarifMaxHint.textContent = '';
                return;
            }

            min = parseFloat(min);
            max = parseFloat(max);

            if (tarifMinHint) tarifMinHint.textContent = `Doit être compris entre ${formatNombre(min)} et ${formatNombre(max)}`;
            if (tarifMaxHint) tarifMaxHint.textContent = `Doit être compris entre ${formatNombre(min)} et ${formatNombre(max)}`;

            tarifMinEl.value = min;
            tarifMaxEl.value = max;
            tarifMinDisplay.value = formatNombre(min);
            tarifMaxDisplay.value = formatNombre(max);

            tarifMinEl.dataset.currentMin = min; tarifMinEl.dataset.currentMax = max;
            tarifMaxEl.dataset.currentMin = min; tarifMaxEl.dataset.currentMax = max;

            if (!tarifMinDisplay.dataset.listenerAttached) {
                tarifMinDisplay.addEventListener('blur', () => {
                    validateTarifInput(tarifMinEl, tarifMinDisplay,
                        parseFloat(tarifMinEl.dataset.currentMin),
                        parseFloat(tarifMinEl.dataset.currentMax));
                });
                tarifMinDisplay.dataset.listenerAttached = 'true';
            }
            if (!tarifMaxDisplay.dataset.listenerAttached) {
                tarifMaxDisplay.addEventListener('blur', () => {
                    validateTarifInput(tarifMaxEl, tarifMaxDisplay,
                        parseFloat(tarifMaxEl.dataset.currentMin),
                        parseFloat(tarifMaxEl.dataset.currentMax));
                });
                tarifMaxDisplay.dataset.listenerAttached = 'true';
            }
        }

        function validateTarifInput(hiddenEl, displayEl, min, max) {
            let val = parseFloat(hiddenEl.value);
            if (isNaN(val)) return;

            if (val < min) val = min;
            else if (val > max) val = max;

            const tarifMinEl = document.getElementById('tarif_min');
            const tarifMaxEl = document.getElementById('tarif_max');
            let vMin = parseFloat(tarifMinEl.value);
            let vMax = parseFloat(tarifMaxEl.value);

            if (!isNaN(vMin) && !isNaN(vMax) && vMin > vMax) {
                if (hiddenEl === tarifMinEl) vMax = vMin;
                else vMin = vMax;
                tarifMinEl.value = vMin;
                tarifMaxEl.value = vMax;
                document.getElementById('tarif_min_display').value = formatNombre(vMin);
                document.getElementById('tarif_max_display').value = formatNombre(vMax);
            } else {
                hiddenEl.value = val;
                displayEl.value = formatNombre(val);
            }
        }

        // Formate un nombre avec séparateurs de milliers (espace)
        function formatNombre(val) {
            val = val.toString().replace(/\D/g, ''); // retire tout sauf les chiffres
            if (!val) return '';
            return parseInt(val, 10).toLocaleString('fr-FR');
        }

        // Retire les séparateurs pour obtenir la valeur brute
        function unformatNombre(val) {
            return val.toString().replace(/\s/g, '');
        }

        // Attache le formatage live à un champ display + son champ hidden
        function attachFormatage(displayId, hiddenId) {
            const displayEl = document.getElementById(displayId);
            const hiddenEl  = document.getElementById(hiddenId);

            displayEl.addEventListener('input', function() {
                const raw = unformatNombre(this.value);
                this.value = formatNombre(raw);
                hiddenEl.value = raw;
            });

            // Formatage initial (si valeur préremplie en mode édition)
            if (displayEl.value) {
                const raw = unformatNombre(displayEl.value);
                displayEl.value = formatNombre(raw);
                hiddenEl.value = raw;
            }
        }

        attachFormatage('tarif_min_display', 'tarif_min');
        attachFormatage('tarif_max_display', 'tarif_max');

        // --- 3. CHARGEMENT PARAMS ---
        async function loadParams(typeId) {
            if (!typeId) { container.empty(); return; }

            container.html(`<div>Chargement...</div>`);

            try {
                const res  = await fetch(`${baseUrl}/types_dispositif/${typeId}/params`);
                const data = await res.json();
                
                renderPhotoInputs(data.nb_max_photo ?? 4);
                setTarifLimits(data.tarif_min, data.tarif_max);
                
                // ✅ En mode Edit : écraser les valeurs par défaut avec les valeurs existantes
                @if($isEdit)
                    const tarifMinEl = document.getElementById('tarif_min');
                    const tarifMaxEl = document.getElementById('tarif_max');
                    const existingTarifMin = {{ $dispositif->tarif_min ?? 'null' }};
                    const existingTarifMax = {{ $dispositif->tarif_max ?? 'null' }};

                    if (existingTarifMin !== null) tarifMinEl.value = existingTarifMin;
                    if (existingTarifMax !== null) tarifMaxEl.value = existingTarifMax;
                @endif

                container.empty();
                
                data.params.forEach(param => {
                    const paramData  = existingParams[param.id] ?? {};
                    const value      = paramData.value ?? '';
                    const unit       = param.numeric_value_unit ?? null;
                    const isNumeric  = ['int', 'decimal'].includes(param.value_type);
                    const inputName  = `params[${param.id}][value]`;
                    let inputHtml    = '';

                    if (param.list_values) {
                        inputHtml = `
                            <select name="${inputName}" class="form-select">
                                <option value="">Sélectionner</option>
                                ${param.list_values.split(',').map(v =>
                                    `<option value="${v.trim()}" ${v.trim() == value ? 'selected' : ''}>${v.trim()}</option>`
                                ).join('')}
                            </select>`;
                    } else {
                        const type = isNumeric ? 'number'
                            : param.value_type === 'date'     ? 'date'
                            : param.value_type === 'datetime' ? 'datetime-local'
                            : 'text';

                        inputHtml = `
                            <div class="input-group">
                                <input type="${type}"
                                       class="form-control"
                                       name="${inputName}"
                                       value="${value}"
                                       ${param.value_type === 'decimal' ? 'step="0.01"' : ''}>
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
                console.error('Erreur détaillée:', e);
                container.html(`<div class="text-danger">Erreur chargement</div>`);
            }
        }

        // --- 4. SÉLECTION CATÉGORIE & CASCADE ---
        window.selectCategorie = function(catId, preselectTypeId = null) {
            document.getElementById('categorie_id').value = catId;

            document.querySelectorAll('.categorie-sidebar-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.querySelector(`.categorie-sidebar-item[data-id="${catId}"] .categorie-sidebar-btn`);
            if (activeBtn) activeBtn.classList.add('active');

            const typeSelect = document.getElementById('types_dispositif_id');
            typeSelect.innerHTML = '<option value="">Chargement...</option>';

            fetch(`{{ url('types_dispositif/by-categorie') }}/${catId}`)
                .then(r => r.json())
                .then(data => {
                    typeSelect.innerHTML = '<option value="">Sélectionner</option>';
                    data.forEach(type => {
                        const opt = document.createElement('option');
                        opt.value       = type.id;
                        opt.textContent = type.nom;
                        if (preselectTypeId && type.id == preselectTypeId) opt.selected = true;
                        typeSelect.appendChild(opt);
                    });

                    if (preselectTypeId && typeSelect.value) {
                        loadParams(typeSelect.value);
                    }
                });
        };

        // Init mode Edit
        @if($isEdit)
            const initCatId  = {{ $dispositif->type_dispositif->categorie_id ?? 'null' }};
            const initTypeId = {{ $dispositif->types_dispositif_id ?? 'null' }};
            if (initCatId) selectCategorie(initCatId, initTypeId);
        @endif

        // Changement manuel du type
        if (typeSelectEl) {
            typeSelectEl.addEventListener('change', function() {
                if (this.value) loadParams(this.value);
            });
        }

        // --- 5. MODALE DE CONFIRMATION ---
        dispositifForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!typeSelectEl.value) {
                alert("Veuillez sélectionner un type de matériel.");
                return;
            }

            const deviseSymbol = "{{ auth()->user()->pays?->devise?->symbol ?? 'F CFA' }}";

            const activeCatName = document.querySelector('.categorie-sidebar-btn.active span')?.textContent ?? 'N/A';

            // Fonction utilitaire pour formater un montant avec séparateurs de milliers
            function formatMontant(val) {
                if (val === '' || val === null || isNaN(val)) return null;
                return parseFloat(val).toLocaleString('fr-FR');
            }

            // Récupération et formatage de la plage de prix
            const prixMinRaw = $("#tarif_min").val();
            const prixMaxRaw = $("#tarif_max").val();
            const prixMin = formatMontant(prixMinRaw);
            const prixMax = formatMontant(prixMaxRaw);

            let prixHtml = 'N/A';
            if (prixMin && prixMax) {
                prixHtml = `${prixMin} ${deviseSymbol} - ${prixMax} ${deviseSymbol} / jour`;
            } else if (prixMin) {
                prixHtml = `À partir de ${prixMin} ${deviseSymbol}`;
            } else if (prixMax) {
                prixHtml = `Jusqu'à ${prixMax} ${deviseSymbol}`;
            }

            let summaryHtml = `
                <div class="col-12 border-bottom py-1">
                    <strong class="summary-label">Catégorie :</strong> <span>${activeCatName}</span>
                </div>
                <div class="col-12 border-bottom py-1">
                    <strong class="summary-label">Type :</strong> <span>${$("#types_dispositif_id option:selected").text()}</span>
                </div>
                <div class="col-12 border-bottom py-1">
                    <strong class="summary-label">Marque :</strong> <span>${$("#marque").val() || 'N/A'}</span>
                </div>
                <div class="col-12 border-bottom py-1">
                    <strong class="summary-label">Modèle :</strong> <span>${$("#modele").val() || 'N/A'}</span>
                </div>
                <div class="col-12 border-bottom py-1">
                    <strong class="summary-label">Prix de location :</strong> <span>${prixHtml}</span>
                </div>
                <div class="col-12"><hr><h6>Paramètres techniques :</h6><ul class="small">`;

            $("#params-container .col-md-6").each(function() {
                const label = $(this).find('label').text().replace('*', '').trim();
                const input = $(this).find('input:not([type=hidden]), select');
                let val = input.is('select') ? input.find('option:selected').text() : input.val();

                if (val && val !== 'Sélectionner') {
                    // Formatage avec séparateurs de milliers si c'est un champ numérique
                    if (input.attr('type') === 'number' && !isNaN(val) && val !== '') {
                        val = parseFloat(val).toLocaleString('fr-FR');
                    }
                    const unit = $(this).find('.input-group-text').text();
                    summaryHtml += `<li><strong>${label} :</strong> ${val} ${unit}</li>`;
                }
            });

            summaryHtml += `</ul></div>`;

            $('#summaryContent').html(summaryHtml);
            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        });

        document.getElementById('finalSubmitBtn').addEventListener('click', function() {
            bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
            executeAjaxSubmit();
        });

        // --- 6. ENVOI AJAX ---
        function executeAjaxSubmit() {
            const formData        = new FormData(dispositifForm);
            const xhr             = new XMLHttpRequest();
            const btn             = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar     = document.getElementById('progressBar');

            progressContainer.style.display = 'block';
            btn.disabled = true;

            xhr.open('POST', dispositifForm.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.addEventListener('progress', e => {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressBar.innerText   = percent + '%';
                }
            });

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    window.location.href = "{{ route('user.dispositifs.index') }}";
                } else {
                    btn.disabled = false;
                    progressContainer.style.display = 'none';

                    // ✅ Afficher la réponse brute pour déboguer
                    console.error('Statut :', xhr.status);
                    console.error('Réponse brute :', xhr.responseText);

                    try {
                        handleErrors(JSON.parse(xhr.responseText));
                    } catch (e) {
                        // Si la réponse n'est pas du JSON (ex: exception Laravel)
                        console.error('Réponse non-JSON (exception Laravel) :', xhr.responseText);
                        alert('Erreur serveur 500 — voir la console pour les détails.');
                    }
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
                    const sanitized = field.replace(/\./g, '_');
                    $(`[name="${field}"]`).addClass('is-invalid');
                    $(`#error-${sanitized}`).text(response.errors[field][0]).show();
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    });

    // --- FONCTIONS GLOBALES ---
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
