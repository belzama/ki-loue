/**
 * Gestionnaire générique de formulaires AJAX pour Laravel
 */
const LaravelFormHandler = {
    
    init() {
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('ajax-form')) {
                e.preventDefault();
                this.handleSubmission(e.target);
            }
        });
    },

    async handleSubmission(form) {
        const btn = form.querySelector('button[type="submit"]');
        const originalBtnText = btn.innerHTML;
        
        // 1. Reset des états
        this.resetErrors(form);
        this.setLoading(btn, true);

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: form.method || 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const result = await response.json();

            if (response.status === 422) {
                // Erreurs de validation Laravel
                this.displayErrors(form, result.errors);
            } else if (!response.ok) {
                // Autre erreur (500, etc.)
                alert("Une erreur serveur est survenue.");
            } else {
                // Succès
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else if (result.message) {
                    alert(result.message);
                }
            }
        } catch (error) {
            console.error("Erreur réseau :", error);
        } finally {
            this.setLoading(btn, false, originalBtnText);
        }
    },

    displayErrors(form, errors) {
        let firstErrorField = null;

        for (const [field, messages] of Object.entries(errors)) {
            // On gère les champs simples et les tableaux (ex: photos.0)
            const inputName = field.includes('.') ? field.split('.')[0] + '[]' : field;
            const errorId = `error-${field.replace(/\./g, '_')}`;
            
            const input = form.querySelector(`[name="${field}"]`) || form.querySelector(`[name="${inputName}"]`);
            const errorDiv = form.querySelector(`#${errorId}`);

            if (input) {
                input.classList.add('is-invalid');
                if (!firstErrorField) firstErrorField = input;
            }

            if (errorDiv) {
                errorDiv.innerText = messages[0];
                errorDiv.style.display = 'block';
            }
        }

        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    },

    resetErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.innerText = '';
            el.style.display = 'none';
        });
    },

    setLoading(btn, isLoading, originalText = '') {
        if (isLoading) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...`;
        } else {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
};

// Lancement automatique
document.addEventListener('DOMContentLoaded', () => LaravelFormHandler.init());