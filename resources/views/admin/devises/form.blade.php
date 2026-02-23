@csrf

<div class="mb-3">
    <label>Code <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control" 
           value="{{ old('code', $devise->code ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Libellé <span class="text-danger">*</span></label>
    <input type="text" name="libelle" class="form-control" 
           value="{{ old('libelle', $devise->libelle ?? '') }}" required>
</div>
<div class="mb-3">
    <label>Symbol <span class="text-danger">*</span></label>
    <input type="text" name="symbol" class="form-control" 
           value="{{ old('symbol', $devise->symbol ?? '') }}" required>
</div>

<button type="submit" class="btn btn-success">Enregistrer</button>
