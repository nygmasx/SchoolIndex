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
    const courseDropdownToggle = document.getElementById('CourseDropdownToggle');
    const coursesList = document.getElementById('courses-list');
    
    // Ajouter un gestionnaire d'événement de clic sur le bouton de liste de cours
    courseDropdownToggle.addEventListener('click', function (event) {
        event.stopPropagation(); // Empêcher la propagation de l'événement de clic
        toggleList(coursesList);
        hideList(optionsList); // Cacher la liste d'options si elle est ouverte
    });
    
    // Ajouter un gestionnaire d'événement de clic sur l'image du profil
    profileImg.addEventListener('click', function (event) {
        event.stopPropagation(); // Empêcher la propagation de l'événement de clic
        toggleList(optionsList);
        hideList(coursesList); // Cacher la liste des cours si elle est ouverte
    });
    
    // Fonction pour afficher ou masquer la liste
    function toggleList(listElement) {
        listElement.classList.toggle('hidden');
    }
    
    // Fonction pour masquer une liste spécifique
    function hideList(listElement) {
        listElement.classList.add('hidden');
    }
    
    // Écouter les clics sur l'ensemble du document pour masquer les listes si nécessaire
    document.addEventListener('click', function (event) {
        const isClickInsideDropdown = courseDropdownToggle.contains(event.target);
        const isClickInsideCoursesList = coursesList.contains(event.target);
        const isClickInsideOptions = optionsList.contains(event.target);
        if (!isClickInsideDropdown && !isClickInsideCoursesList) {
            hideList(coursesList);
        }
        if (!isClickInsideOptions) {
            hideList(optionsList);
        }
    });
        
});
