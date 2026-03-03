document.addEventListener("DOMContentLoaded", function () {

    const modalElement = document.getElementById("contactModal");
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const modalBody = document.getElementById("contactModalBody");

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    function openContactModal(url, button) {

        // Sécurise contre double clic
        button.disabled = true;
        button.innerHTML = "Réservation en cours...";

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        })
        .then(async response => {

            if (!response.ok) {
                const text = await response.text();
                console.log("Erreur serveur :", text);
                throw new Error("Erreur serveur");
            }

            return response.json();
        })
        .then(data => {

            modalBody.innerHTML = "";

            if (!data.success) {
                modalBody.innerHTML =
                    `<p class="text-danger text-center">
                        Une erreur est survenue.
                    </p>`;
                modal.show();
                return;
            }

            const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

            if (data.owner?.contact && isMobile) {
                modalBody.innerHTML += `
                    <a href="tel:${data.owner.contact}"
                    class="btn btn-primary">
                        Appeler
                    </a>
                `;
            }

            if (data.owner?.contact) {
                modalBody.innerHTML += `
                    <a href="https://wa.me/${data.owner.contact}?text=${data.message}"
                    target="_blank"
                    class="btn btn-success">
                        WhatsApp
                    </a>
                `;
            }

            if (data.owner?.email) {
                modalBody.innerHTML += `
                    <a href="mailto:${data.owner.email}"
                    class="btn btn-warning">
                        Email
                    </a>
                `;
            }

            modal.show();
        })
        .catch(error => {

            console.error(error);

            modalBody.innerHTML =
                `<p class="text-danger text-center">
                    Une erreur est survenue.
                </p>`;

            modal.show();
        })
        .finally(() => {
            // 🔥 Toujours réactiver le bouton
            button.disabled = false;
            button.innerHTML = "Contacter";
        });
    }

    // Listener global
    document.addEventListener("click", function (e) {

        const btn = e.target.closest(".contact-btn");
        if (!btn) return;

        const url = btn.dataset.url;
        if (!url) return;

        openContactModal(url, btn);
    });

});