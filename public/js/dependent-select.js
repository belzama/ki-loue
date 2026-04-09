document.addEventListener('DOMContentLoaded', function () {
    
    function resetSelect(select, placeholder = 'Sélectionner') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
    }

    function updateLabels(select) {
        // On vérifie que c'est bien le select pays
        if (select.id !== 'pays_id') return;
        
        // On récupère l'option actuellement sélectionnée
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        // Récupération des données via dataset (plus propre)
        const division = selectedOption.dataset.division || 'Région';
        const sousDivision = selectedOption.dataset.sousDivision || 'Préfecture';

        // Mise à jour des libellés dans le DOM
        const labelDiv = document.getElementById('label_division');
        const labelSousDiv = document.getElementById('label_sous_division');

        if (labelDiv) labelDiv.innerText = division;
        if (labelSousDiv) labelSousDiv.innerText = sousDivision;
    }

    async function loadOptions(parent) {
        const childId = parent.dataset.child;
        const url = parent.dataset.url;
        const child = document.getElementById(childId);

        if (!child || !url) return;

        if (!parent.value) {
            resetSelect(child);
            child.dispatchEvent(new Event('change', { bubbles: true }));
            return;
        }

        try {
            const response = await fetch(url + parent.value);
            const data = await response.json();

            resetSelect(child);

            data.forEach(item => {
                const opt = new Option(item.nom, item.id);
                // Si on a des data-attributes à passer à l'enfant (ex: pour une cascade triple)
                if(item.division) opt.dataset.division = item.division; 
                child.add(opt);
            });

            if (child.dataset.selected) {
                child.value = child.dataset.selected;
                // On vide après usage pour éviter les conflits
                child.dataset.selected = ''; 
            }

            child.dispatchEvent(new CustomEvent('select:ready', { 
                detail: { value: child.value } 
            }));

            child.dispatchEvent(new Event('change', { bubbles: true }));

        } catch (e) {
            console.error("Erreur AJAX:", e);
        }
    }

    // --- INITIALISATION ET ÉVÉNEMENTS ---

    // On attache l'événement une seule fois par select
    document.querySelectorAll('select[data-child]').forEach(select => {
        select.addEventListener('change', function () {
            if (this.id === 'pays_id') {
                updateLabels(this);
            }
            // On ne vide la cascade que si le changement est manuel (déclenché par l'utilisateur)
            // loadOptions gérera la suite
            loadOptions(this);
        });
    });

    // Lancement au chargement de la page (Mode Édition ou Pays par défaut)
    const paysSelect = document.getElementById('pays_id');
    if (paysSelect) {
        // Appliquer les libellés immédiatement selon le pays sélectionné par défaut (Togo ?)
        updateLabels(paysSelect);
        
        if (paysSelect.value) {
            loadOptions(paysSelect);
        }
    }

    // Forcer le chargement des types au démarrage si une catégorie existe
    const catSelectEl = document.getElementById('categorie_id');
    if (catSelectEl && catSelectEl.value) {
        // Cette fonction vient de votre fichier dependent-select.js
        // Elle va remplir le select des types, qui lui-même déclenchera 'select:ready'
        if (typeof loadOptions === "function") {
            loadOptions(catSelectEl);
        }
    }
});