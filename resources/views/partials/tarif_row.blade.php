<div class="row border p-2 mb-2 tarif-item">

    <div class="col-md-3">
        <input type="text"
               name="tarifs[{{ $index }}][designation]"
               class="form-control"
               placeholder="Désignation"
               value="{{ $tarif->designation ?? '' }}" required>
    </div>   

    <div class="col-md-3">
        <input type="number"
               name="tarifs[{{ $index }}][tranche_debut]"
               class="form-control"
               placeholder="Début"
               value="{{ $tarif->tranche_debut ?? '' }}">
    </div> 

    <div class="col-md-2">
        <input type="number"
               name="tarifs[{{ $index }}][tranche_fin]"
               class="form-control"
               placeholder="Fin"
               value="{{ $tarif->tranche_fin ?? '' }}">
    </div>

    <div class="col-md-2">
        <input type="number"
               name="tarifs[{{ $index }}][tranche_valeur]"
               class="form-control"
               placeholder="Fin"
               value="{{ $tarif->tranche_valeur ?? '' }}">
    </div>

    <div class="col-md-1">
        <button type="button"
                class="btn btn-outline-danger btn-sm"
                onclick="removeTarif(this)"
                title="Supprimer">
            <i class="bi bi-trash"></i>
        </button>
    </div>

</div>