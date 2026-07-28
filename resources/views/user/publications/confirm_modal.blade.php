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
                
                @php $abonnementActif = $dispositif->abonnementActif; @endphp
                {{-- Période abonnement --}}
                @if($abonnementActif)
                    <div class="mb-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                            <i class="bi bi-bell-fill me-1"></i> Abonné
                        </span>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-calendar-range me-1"></i>
                            {{ \Carbon\Carbon::parse($abonnementActif->date_debut)->format('d/m/Y') }}
                            →
                            {{ \Carbon\Carbon::parse($abonnementActif->date_fin)->format('d/m/Y') }}
                        </div>
                    </div>
                @else
                    <div class="mb-3">
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                            <i class="bi bi-bell-slash me-1"></i> Aucun abonnement actif
                        </span>
                    </div>
                @endif

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
