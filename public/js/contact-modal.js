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

            if (data.owner && data.owner.telephone) {
                if (isMobile) {
                    modalBody.innerHTML += `
                        <a href="tel:${data.owner.telephone}"
                        class="btn btn-primary">
                            <i class="bi bi-telephone-fill"></i> Appeler
                        </a>
                    `;

                    modalBody.innerHTML += `
                        <a href="sms:${data.owner.telephone}"
                        class="btn btn-secondary">
                            <i class="bi bi-chat-dots-fill"></i> Envoyer SMS
                        </a>
                    `;
                } else {
                    modalBody.innerHTML += `
                        <a href="#"
                            class="btn btn-primary show-phone-btn"
                            data-phone="${data.owner.telephone}">
                            <i class="bi bi-telephone"></i> Afficher le numéro
                        </a>
                    `;
                    
                }
            }

            if (data.owner && data.owner.whatsapp) {
                modalBody.innerHTML += `
                    <a href="https://wa.me/${data.owner.whatsapp}?text=${data.message}"
                    target="_blank"
                    class="btn btn-success">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                `;
            }

            if (data.owner && data.owner.email) {
                modalBody.innerHTML += `
                    <a href="mailto:${data.owner.email}"
                    class="btn btn-warning">
                        <i class="bi bi-envelope-fill"></i> Envoyer Email
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
    
    document.addEventListener("click", function(e){

        const phoneBtn = e.target.closest(".show-phone-btn");
        if (!phoneBtn) return;

        e.preventDefault();

        const phone = phoneBtn.dataset.phone;

        phoneBtn.innerHTML =
            `<i class="bi bi-telephone-fill"></i> ${phone}`;
    });
    


    // Listener global
    document.addEventListener("click", function (e) {

        const btn = e.target.closest(".contact-btn");
        if (!btn) return;

        const url = btn.dataset.url;
        if (!url) return;

        openContactModal(url, btn);
    });

});