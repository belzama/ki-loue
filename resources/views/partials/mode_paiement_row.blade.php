<div class="row border p-2 mb-2 mode-item">

    <div class="col-md-3">
        <input type="text"
               name="mode_paiements[{{ $index }}][designation]"
               class="form-control"
               placeholder="Désignation"
               value="{{ $mode->designation ?? '' }}" required>
    </div>   

    <div class="col-md-3">
        <select name="mode_paiements[{{ $index }}][type]"
                class="form-select" required>
            <option value="Mobile Money" {{ (isset($mode) && $mode->type=='Mobile Money') ? 'selected' : '' }}>Mobile Money</option>
            <option value="Visa Card" {{ (isset($mode) && $mode->type=='Visa Card') ? 'selected' : '' }}>Visa Card</option>
            <option value="Wallet" {{ (isset($mode) && $mode->type=='Wallet') ? 'selected' : '' }}>Wallet</option>
            <option value="Espèce" {{ (isset($mode) && $mode->type=='Espèce') ? 'selected' : '' }}>Espèce</option>
            <option value="Chèque" {{ (isset($mode) && $mode->type=='Chèque') ? 'selected' : '' }}>Chèque</option>
       </select>
    </div>

    <div class="col-md-3">
        <input type="text"
               name="mode_paiements[{{ $index }}][api_url]"
               class="form-control"
               placeholder="Url de l'API"
               value="{{ $mode->api_url ?? '' }}">
    </div> 

    <div class="col-md-2">
        <input type="text"
               name="mode_paiements[{{ $index }}][numero_compte]"
               class="form-control"
               placeholder="Numero de compte de paiement"
               value="{{ $mode->numero_compte ?? '' }}">

    </div>

    <div class="col-md-1">
        <button type="button"
                class="btn btn-outline-danger btn-sm"
                onclick="removeMode(this)"
                title="Supprimer">
            <i class="bi bi-trash"></i>
        </button>
    </div>

</div>
