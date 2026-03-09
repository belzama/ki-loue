@csrf

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label>Pays <span class="text-danger">*</span></label>
        <select name="pays_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($pays_list as $p) 
                <option value="{{ $p->id }}" 
                    {{ (old('pays_id', $region->pays_id ?? '') == $p->id) ? 'selected' : '' }}>
                    {{ $p->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" 
            value="{{ old('nom', $region->nom ?? '') }}" required>
    </div>
</div>


<button type="submit" class="btn btn-success">Enregistrer</button>
