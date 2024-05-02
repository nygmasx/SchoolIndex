import './bootstrap.js';
import Turn from '@domchristie/turn'
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './vendor/@domchristie/turn/dist/turn.css'


Turn.start();
console.log("holla")
document.addEventListener('turbo:load', function () {
    const profileImg = document.getElementById('profile-img');
    const optionsList = document.getElementById('options-list');

    // Ajouter un gestionnaire d'événement de clic sur l'image du profil
    profileImg.addEventListener('click', function (event) {
        event.stopPropagation(); // Empêcher la propagation de l'événement de clic
        toggleOptionsList();
    });

    // Ajouter un gestionnaire d'événement de clic sur la liste des options
    optionsList.addEventListener('click', function (event) {
        event.stopPropagation(); // Empêcher la propagation de l'événement de clic
    });

    // Ajouter un gestionnaire d'événement de clic sur le document pour masquer la liste des options
    document.addEventListener('click', function (event) {
        if (!optionsList.contains(event.target)) {
            hideOptionsList();
        }
    });

    // Fonction pour afficher ou masquer la liste des options
    function toggleOptionsList() {
        optionsList.classList.toggle('fade-in');
        optionsList.classList.toggle('hidden');
    }

    // Fonction pour masquer la liste des options
    function hideOptionsList() {
        optionsList.classList.add('hidden');
        optionsList.classList.remove('fade-in');
    }
});


//Navigation responsive page admin

document.getElementById('pageSelect').addEventListener('change', function () {
    var page = this.value;
    switch (page) {
        case 'contributeurs':
            window.location.href = '#';
            break;
        case 'exercices':
            window.location.href = '#';
            break;
        // Continuez avec les autres cas si nécessaire
    }
});

// Datatable

document.addEventListener("DOMContentLoaded", function () {
    const customSelect = document.getElementById('customSelect');
    const selectItems = document.querySelector('.select-items');
    const arrowDown = document.querySelector('.arrow-down');
    const selectedLevel = document.querySelector('.select-selected span'); // Sélectionne l'élément qui affiche le niveau sélectionné

    customSelect.addEventListener('click', function () {
        selectItems.classList.toggle('hidden');
        arrowDown.classList.toggle('arrow-up');
    });

    const topLevelItems = selectItems.querySelectorAll('.flex.items-center.justify-between');
    topLevelItems.forEach(function (item) {
        item.addEventListener('click', function (event) {
            const subItems = item.nextElementSibling;
            if (subItems) {
                subItems.classList.toggle('hidden');
                arrowDown.classList.toggle('arrow-up');
                selectedLevel.textContent = item.querySelector('span').textContent; // Met à jour le texte avec le niveau sélectionné
                event.stopPropagation();
            }
        });
    });

    document.addEventListener('click', function (event) {
        const targetElement = event.target;
        if (!customSelect.contains(targetElement)) {
            selectItems.classList.add('hidden');
            arrowDown.classList.remove('arrow-up');
        }
    });
});
