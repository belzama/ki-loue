<!-- resources/views/auth/verify-code.blade.php -->
@extends('layouts.app') {{-- adaptez à votre layout --}}

@section('content')
<div class="container text-center" style="margin-top: 100px;">
    <p class="text-muted">Vérification de votre email en cours...</p>
</div>

<!-- ✅ MODAL DE VÉRIFICATION -->
<div class="modal fade" id="verifyEmailModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" id="closeModalBtn" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 p-5 text-center">

                <h3 class="mb-2">📬 Vérifiez votre email</h3>
                <p class="text-muted">
                    Un code à 6 chiffres a été envoyé à votre adresse.<br>
                    Il expire dans <strong>10 minutes</strong>.
                </p>

                <div id="alertBox" class="alert d-none" role="alert"></div>

                <form id="verifyForm">
                    @csrf
                    <input
                        type="text"
                        id="codeInput"
                        name="code"
                        maxlength="6"
                        inputmode="numeric"
                        placeholder="_ _ _ _ _ _"
                        class="form-control text-center mb-3"
                        style="font-size:32px; letter-spacing:12px; font-weight:bold;"
                        autofocus
                    >

                    <button type="submit" class="btn btn-success w-100 mb-2" id="verifyBtn">
                        ✅ Vérifier mon compte
                    </button>
                </form>

                <button type="button" class="btn btn-link text-muted" id="resendBtn">
                    🔄 Renvoyer le code
                </button>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('verifyEmailModal'));
    modal.show();

    const form       = document.getElementById('verifyForm');
    const alertBox    = document.getElementById('alertBox');
    const verifyBtn   = document.getElementById('verifyBtn');
    const resendBtn   = document.getElementById('resendBtn');
    const codeInput   = document.getElementById('codeInput');
    const closeModalBtn = document.getElementById('closeModalBtn');

    function showAlert(message, type = 'danger') {
        alertBox.className = `alert alert-${type}`;
        alertBox.textContent = message;
        alertBox.classList.remove('d-none');
    }

    // Soumission du code
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        verifyBtn.disabled = true;
        verifyBtn.textContent = 'Vérification...';

        fetch('{{ route("verification.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ code: codeInput.value })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200) {
                showAlert('Email vérifié ! Redirection...', 'success');
                setTimeout(() => window.location.href = body.redirect, 1000);
            } else {
                showAlert(body.message || 'Code invalide ou expiré.');
                codeInput.value = '';
                codeInput.focus();
            }
        })
        .catch(() => showAlert('Une erreur est survenue.'))
        .finally(() => {
            verifyBtn.disabled = false;
            verifyBtn.textContent = '✅ Vérifier mon compte';
        });
    });

    // Renvoi du code
    resendBtn.addEventListener('click', function () {
        resendBtn.disabled = true;
        resendBtn.textContent = 'Envoi...';

        fetch('{{ route("verification.resend") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            showAlert(body.message, status === 200 ? 'success' : 'danger');
        })
        .catch(() => showAlert('Une erreur est survenue.'))
        .finally(() => {
            resendBtn.disabled = false;
            resendBtn.textContent = '🔄 Renvoyer le code';
            setTimeout(() => resendBtn.disabled = false, 5000);
        });
    });

    // Auto-focus suivant / soumission auto à 6 chiffres
    codeInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length === 6) {
            form.dispatchEvent(new Event('submit'));
        }
    });
    
    // Bouton de fermeture : déconnecte l'utilisateur et redirige
    closeModalBtn.addEventListener('click', function () {
        if (confirm('Voulez-vous vraiment annuler la vérification ? Vous serez déconnecté.')) {
            window.location.href = '{{ route("logout.get") }}'; // adapte selon ta route de déconnexion
        }
    });
});
</script>
@endpush