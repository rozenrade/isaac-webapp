/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


// Carousel

let currentIndex = 0; // Initialise l'index de l'image affichée à 0, pour commencer par la première image du carrousel.

const carouselContainer = document.getElementById('carousel-container'); // Récupère l'élément du carrousel par son ID 'carousel-container'.

const slides = carouselContainer.children; // Récupère tous les éléments enfants (les slides/images) à l'intérieur du conteneur du carrousel.

const totalSlides = slides.length; // Calcule le nombre total de slides dans le carrousel.

function showSlide(index) {
    // Boucle à travers toutes les slides pour masquer celles qui ne sont pas actuellement affichées.
    for (let i = 0; i < totalSlides; i++) {
        slides[i].style.display = 'none'; // Masque chaque slide en réglant son display à 'none'.
    }
    slides[index].style.display = 'block'; // Affiche la slide correspondant à l'index donné en réglant son display à 'block'.
}

showSlide(currentIndex); // Affiche la première image (index 0) du carrousel au chargement de la page.

setInterval(() => {
    currentIndex = (currentIndex + 1) % totalSlides; // Incrémente l'index pour passer à la slide suivante, et revient à 0 une fois à la fin.
    showSlide(currentIndex); // Affiche la slide correspondant au nouvel index.
}, 3000); // Répète ce processus toutes les 3000 millisecondes (3 secondes), créant une transition automatique.