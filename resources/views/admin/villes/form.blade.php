@csrf

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Pays/Région <span class="text-danger">*</span></label>
        <select name="pays_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($pays_list as $p)
                <option value="{{ $p->id }}" 
                    {{ (old('pays_id', $ville->pays_id ?? '') == $p->id) ? 'selected' : '' }}>
                    {{ $p->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label>Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" 
            value="{{ old('nom', $ville->nom ?? '') }}" required>
    </div>
</div>


<button type="submit" class="btn btn-success">Enregistrer</button>
