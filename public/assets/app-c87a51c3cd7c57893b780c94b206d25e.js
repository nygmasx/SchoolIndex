import './bootstrap.js';
import Turn from '@domchristie/turn';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './vendor/@domchristie/turn/dist/turn.css';

Turn.start();

document.addEventListener('turbo:load', function() {
    const profileImg = document.getElementById('profile-img');
    const optionsList = document.getElementById('options-list');

    // Ajouter un gestionnaire d'événement de clic sur l'image du profil
    profileImg.addEventListener('click', function(event) {
        event.stopPropagation(); // Empêcher la propagation de l'événement de clic
        toggleOptionsList();
    });

    // Fonction pour afficher ou masquer la liste des options
    function toggleOptionsList() {
        optionsList.classList.toggle('hidden');
    }

    // Écouter les clics sur l'ensemble du document pour masquer la liste des options si nécessaire
    document.addEventListener('click', function(event) {
        const isClickInsideMenu = profileImg.contains(event.target);
        const isClickInsideOptions = optionsList.contains(event.target);
        if (!isClickInsideMenu && !isClickInsideOptions) {
            hideOptionsList();
        }
    });

    // Fonction pour masquer la liste des options
    function hideOptionsList() {
        optionsList.classList.add('hidden');
    }
});