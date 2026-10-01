<div class="row border p-2 mb-2 mode-item align-items-center">

    <div class="col-md-2 text-center">

        <div class="mode-logo-preview mb-1">
            @if(isset($mode) && $mode->logo)
                <img src="{{ asset('storage/' . $mode->logo) }}" class="preview-img">
            @else
                <span class="text-muted small">Aucun logo</span>
            @endif
        </div>

        <input type="file"
               name="mode_paiements[{{ $index }}][logo]"
               class="form-control form-control-sm"
               accept="image/*"
               onchange="previewLogo(this)">

        {{-- Transporte le chemin du logo existant si l'admin ne re-upload rien --}}
        @if(isset($mode) && $mode->logo)
            <input type="hidden"
                   name="mode_paiements[{{ $index }}][logo_existant]"
                   value="{{ $mode->logo }}">
        @endif

    </div>

    <div class="col-md-3">
        <input type="text"
               name="mode_paiements[{{ $index }}][designation]"
               class="form-control"
               placeholder="Désignation"
               value="{{ $mode->designation ?? '' }}"
               required>
    </div>

    <div class="col-md-2">
        <select name="mode_paiements[{{ $index }}][type]" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach(['Mobile Money','Visa Card','Wallet','Espèce','Chèque','Virement'] as $type)
                <option value="{{ $type }}" {{ (isset($mode) && $mode->type == $type) ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <input type="text"
               name="mode_paiements[{{ $index }}][api_url]"
               class="form-control"
               placeholder="URL API"
               value="{{ $mode->api_url ?? '' }}">
    </div>

    <div class="col-md-2">
        <input type="text"
               name="mode_paiements[{{ $index }}][numero_compte]"
               class="form-control"
               placeholder="Numéro compte"
               value="{{ $mode->numero_compte ?? '' }}">
    </div>

    <div class="col-md-1">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeMode(this)">
            <i class="bi bi-trash"></i>
        </button>
    </div>

</div>