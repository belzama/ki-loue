@csrf

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label>Pays <span class="text-danger">*</span></label>
        <select id="pays_id" 
                name="pays_id"
                data-child="region_id"
                data-url="{{ url('regions/by-pays') }}/"
                data-selected="{{ old('region_id', $ville->region_id ?? '') }}" 
                class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($pays_list as $p)
                <option value="{{ $p->id }}" 
                    {{ (old('pays_id', $ville->pays_id ?? '') == $p->id) ? 'selected' : '' }}>
                    {{ $p->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Région <span class="text-danger">*</span></label>
        <select id="region_id"
                name="region_id" 
                data-child="ville_id"
                data-url="{{ url('villes/by-region') }}/"
                data-selected="{{ old('ville_id', $ville->id ?? '') }}"
                class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}" 
                    {{ (old('region_id', $ville->region_id ?? '') == $region->id) ? 'selected' : '' }}>
                    {{ $region->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" 
            value="{{ old('nom', $ville->nom ?? '') }}" required>
    </div>
</div>


<button type="submit" class="btn btn-success">Enregistrer</button>


@section('scripts')
<script src="{{ asset('js/dependent-select.js') }}"></script>
@endsection