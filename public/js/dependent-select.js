document.addEventListener('DOMContentLoaded', function () {

    function resetSelect(select, placeholder = 'Sélectionner') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function loadOptions(parentSelect) {

        return new Promise((resolve, reject) => {

            const childId = parentSelect.dataset.child;
            const url = parentSelect.dataset.url;

            if (!childId || !url) {
                resolve();
                return;
            }

            const childSelect = document.getElementById(childId);
            if (!childSelect) {
                resolve();
                return;
            }

            resetSelect(childSelect, 'Chargement...');

            if (!parentSelect.value) {
                resetSelect(childSelect);
                resolve();
                return;
            }

            fetch(url + parentSelect.value)
                .then(response => response.json())
                .then(data => {

                    resetSelect(childSelect);

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.nom;
                        childSelect.appendChild(option);
                    });

                    // 🔥 Appliquer la valeur sauvegardée automatiquement
                    const selectedValue = childSelect.dataset.selected;

                    if (selectedValue) {
                        childSelect.value = selectedValue;
                        childSelect.dataset.selected = '';
                    }

                    resolve(childSelect);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    resetSelect(childSelect, 'Erreur');
                    reject(error);
                });

        });
    }

    async function restoreChain(select) {

        let current = select;

        while (current && current.value && current.dataset.child) {
            current = await loadOptions(current);
        }
    }

    const dependentSelects = document.querySelectorAll('select[data-child]');

    // Gestion du change
    dependentSelects.forEach(select => {
        select.addEventListener('change', function () {

            // Nettoyer les enfants
            let childId = this.dataset.child;

            while (childId) {
                const child = document.getElementById(childId);
                if (!child) break;

                child.dataset.selected = '';
                resetSelect(child);

                childId = child.dataset.child;
            }

            loadOptions(this);
        });
    });

    // 🔥 Restauration automatique après recherche
    dependentSelects.forEach(select => {
        if (select.value) {
            restoreChain(select);
        }
    });

});