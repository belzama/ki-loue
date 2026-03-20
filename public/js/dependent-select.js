document.addEventListener('DOMContentLoaded', function () {

    function resetSelect(select, placeholder = 'Toutes') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function updateLabels(select) {
        if (select.id !== 'pays_id') return;
        const option = select.options[select.selectedIndex];

        const division = option?.getAttribute('data-division') || 'Région';
        const sousDivision = option?.getAttribute('data-sous-division') || 'Préfecture';

        document.getElementById('label_division').innerText = division;
        document.getElementById('label_sous_division').innerText = sousDivision;
    }

    async function loadOptions(parentSelect) {
        const childId = parentSelect.dataset.child;
        const url = parentSelect.dataset.url;

        if (!childId || !url) return null;

        const child = document.getElementById(childId);
        if (!child) return null;

        resetSelect(child, 'Chargement...');

        if (!parentSelect.value) {
            resetSelect(child);
            return null;
        }

        try {
            const response = await fetch(url + parentSelect.value);
            const data = await response.json();

            resetSelect(child);

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nom;
                child.appendChild(option);
            });

            // restauration valeur sélectionnée
            const selected = child.dataset.selected;
            if (selected) child.value = selected;

            return child;

        } catch (e) {
            console.error('Erreur AJAX:', e);
            resetSelect(child, 'Erreur');
            return null;
        }
    }

    function clearCascade(select) {
        let childId = select.dataset.child;

        while (childId) {
            const child = document.getElementById(childId);
            if (!child) break;

            child.dataset.selected = '';
            resetSelect(child);

            childId = child.dataset.child;
        }
    }

    async function restoreChain(select) {
        let current = select;

        while (current && current.value && current.dataset.child) {
            current = await loadOptions(current);
        }
    }

    // EVENTS
    document.querySelectorAll('select[data-child]').forEach(select => {
        select.addEventListener('change', async function () {
            updateLabels(this);   // 🔥 update labels immédiat
            clearCascade(this);   // 🔥 reset cascade
            await loadOptions(this); // 🔥 charger enfant
        });
    });

    // INIT
    const pays = document.getElementById('pays_id');

    if (pays) {
        updateLabels(pays);

        if (pays.value) {
            restoreChain(pays);
        }
    }

});