@php
    $isEdit = isset($dispositif);
@endphp

{{-- Afficher les erreurs --}}
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

    {{-- Type de dispositif --}}
    <div class="mb-3">
        <label class="form-label">Type de dispositif <span class="text-danger">*</span></label>
        <select name="types_dispositif_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}"
                    {{ old('types_dispositif_id', $dispositif->types_dispositif_id ?? '') == $type->id ? 'selected' : '' }}>
                    {{ $type->nom }} ({{ $type->categorie->nom ?? '' }})
                </option>
            @endforeach
        </select>
    </div>

    {{-- Numéro immatriculation --}}
    <div class="mb-3">
        <label class="form-label">N° d'immatriculation</label>
        <input type="text" name="numero_immatriculation" class="form-control" value="{{ old('numero_immatriculation', $dispositif->numero_immatriculation ?? '') }}">
    </div>

    {{-- Marque et model --}}
    <div class="mb-3">
        <label class="form-label">Désignation (Marque & modèle) <span class="text-danger">*</span></label>
        <input type="text" name="designation" class="form-control" value="{{ old('designation', $dispositif->designation ?? '') }}">
    </div>
    
    {{-- Container pour les paramètres dynamiques --}}
    <div id="params-container" class="mt-3"></div>

    {{-- Statut --}}
    <div class="mb-3">
        <label class="form-label">Statut <span class="text-danger">*</span></label>
        <select name="statut" class="form-select" required>
            @foreach(['Actif','Inactif','Suspendu'] as $statut)
                <option value="{{ $statut }}"
                    {{ old('statut', $dispositif->statut ?? '') == $statut ? 'selected' : '' }}>
                    {{ $statut }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Photos --}}
    <div class="mb-3">
        <label class="form-label">Photos</label>
        <input type="file" name="photos[]" multiple class="form-control">        
    </div>

    {{-- Boutons --}}
    <div class="mt-3">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> {{ $isEdit ? 'Enregistrer' : 'Créer' }}
        </button>
        <a href="{{ route('user.dispositifs.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</form>

@if($isEdit && $dispositif->photos->count())
<div class="row mt-3">
    @foreach($dispositif->photos as $photo)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">

                <img src="{{ asset('storage/'.$photo->path) }}"
                    class="card-img-top"
                    style="height:120px; object-fit:cover;">

                <div class="card-body p-2 text-center">

                    {{-- Badge photo principale --}}
                    @if($photo->is_cover)
                        <span class="badge bg-success mb-2">Photo principale</span>
                    @endif

                    {{-- Supprimer --}}
                    <form action="{{ route('user.dispositifs.photos.destroy', $photo) }}"
                        method="POST"
                        onsubmit="return confirm('Supprimer cette photo ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>

                </div>
            </div>
        </div>
    @endforeach
</div>
@endif

{{-- Toujours définir existingParams --}}
<script>
    let existingParams = @json(
        isset($dispositif)
            ? $dispositif->params->pluck('value','name')
            : []
    );
</script>

{{-- Script qui génère les paramètres dynamiques --}}
<script>
let types = @json($types);

function renderParams(typeId)
{
    let container = document.getElementById('params-container');
    container.innerHTML = '';

    let type = types.find(t => t.id == typeId);
    if (!type || !type.params) return;

    type.params.forEach(param => {

        let inputHtml = '';
        let value = existingParams[param.name] ?? '';
        let requiredMark = param.required ? ' <span class="text-danger">*</span>' : '';
        let requiredAttr = param.required ? 'required' : '';

        // ⚡ Liste déroulante si list_values
        if(param.list_values && param.list_values.trim() !== '') {
            let options = param.list_values.split(',').map(v => v.trim());
            inputHtml += `<select name="params[${param.name}]" class="form-select" ${requiredAttr}>`;
            inputHtml += `<option value="">Sélectionner</option>`;
            options.forEach(opt => {
                inputHtml += `<option value="${opt}" ${value == opt ? 'selected' : ''}>${opt}</option>`;
            });
            inputHtml += `</select>`;
        } else {
            // ⚡ Sinon input standard
            let inputType = 'text';
            if(param.value_type === 'int' || param.value_type === 'decimal') inputType = 'number';
            if(param.value_type === 'date') inputType = 'date';
            if(param.value_type === 'datetime') inputType = 'datetime-local';

            inputHtml += `<input type="${inputType}" 
                                name="params[${param.name}]" 
                                class="form-control" 
                                value="${value}" 
                                ${requiredAttr}>`;
        }

        let html = `
            <div class="mb-3">
                <label class="form-label">
                    ${param.label ?? param.name}${requiredMark} 
                    ${param.numeric_value_unit ? '('+param.numeric_value_unit+')' : ''}
                </label>
                ${inputHtml}
            </div>
        `;

        container.insertAdjacentHTML('beforeend', html);
    });
}

// ⚡ Initialisation
document.addEventListener('DOMContentLoaded', function() {
    let select = document.querySelector('[name="types_dispositif_id"]');
    if(select.value) renderParams(select.value);

    select.addEventListener('change', function() {
        renderParams(this.value);
    });
});
</script>




