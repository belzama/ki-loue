@if(!empty($pendingVerifications ?? []))
<div class="modal fade" id="modalVerification" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifModalLabel">Vérification requise</h5>
            </div>
            <div class="modal-body">
                <div id="verifAlert" class="alert alert-danger d-none"></div>
                <p>Un code a été envoyé à <strong id="verifTargetLabel"></strong>.</p>
                <input type="text" id="verifCodeInput" class="form-control" maxlength="6" placeholder="Code à 6 chiffres">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" id="verifResendBtn" class="btn btn-link btn-sm">Renvoyer le code</button>
                <button type="button" id="verifSubmitBtn" class="btn btn-success">Vérifier</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const queue = @json($pendingVerifications);
    if (queue.length === 0) return;

    const csrfToken = '{{ csrf_token() }}';
    const modalEl = document.getElementById('modalVerification');
    const modal = new bootstrap.Modal(modalEl);
    const labels = { email: 'votre email', telephone: 'votre téléphone', whatsapp: 'votre WhatsApp' };

    const targetLabel = document.getElementById('verifTargetLabel');
    const codeInput = document.getElementById('verifCodeInput');
    const alertBox = document.getElementById('verifAlert');
    const submitBtn = document.getElementById('verifSubmitBtn');
    const resendBtn = document.getElementById('verifResendBtn');

    let index = 0;

    function showAlert(msg) {
        alertBox.textContent = msg;
        alertBox.classList.remove('d-none');
    }

    function sendCodeFor(type) {
        alertBox.classList.add('d-none');
        codeInput.value = '';

        fetch('{{ route("verification.send") }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type })
        })
        .then(res => res.json())
        .then(body => {
            const label = labels[type] || type;
            targetLabel.textContent = body.contact ? `${label} (${body.contact})` : label;
        });
    }
    sendCodeFor(queue[index]);
    modal.show();

    submitBtn.addEventListener('click', function () {
        submitBtn.disabled = true;

        fetch('{{ route("verification.confirm") }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type: queue[index], code: codeInput.value })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200) {
                index++;
                if (index < queue.length) {
                    sendCodeFor(queue[index]);
                } else {
                    modal.hide();
                    window.location.reload();
                }
            } else {
                showAlert(body.message || 'Code invalide ou expiré.');
            }
        })
        .catch(() => showAlert('Erreur réseau.'))
        .finally(() => submitBtn.disabled = false);
    });

    resendBtn.addEventListener('click', function () {
        resendBtn.disabled = true;
        sendCodeFor(queue[index]);
        setTimeout(() => resendBtn.disabled = false, 5000);
    });
});
</script>
@endif