{{-- MODAL DE CONFIRMATION --}}
<div class="modal fade" id="confirmPubModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirmer la publication</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Vous êtes sur le point de publier ce matériel. Voici le résumé :</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Durée :</span> <strong id="resume_jours">-</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Prix Total :</span> <strong id="resume_prix">-</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Bonus utilisé :</span> <strong id="resume_bonus" class="text-success">-</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <span>À payer :</span> <strong id="resume_cout" class="text-primary fs-5">-</strong>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Modifier</button>
                <button type="button" id="confirmFinalBtn" class="btn btn-success">Confirmer et Publier</button>
            </div>
        </div>
    </div>
</div>
