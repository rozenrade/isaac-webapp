import "./styles/app.css";

// Saving button
document.addEventListener("DOMContentLoaded", () => {
    const saveButton = document.getElementById("save-button");

    // Vérification de l'existence du bouton avant d'ajouter un event listener
    if (saveButton) {
        saveButton.addEventListener("click", async () => {
            // récupérer les id des items
            const itemIds = Array.from(document.querySelectorAll(".item")).map(
                (item) => item.id
            );
            try {
                const response = await fetch("/random/save", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ items: itemIds }),
                });

                const result = await response.json();

                if (response.ok) {
                    alert(result.success);
                } else {
                    alert(result.error);
                }
            } catch (error) {
                console.log("An error occurred while saving the build:", error);
            }
        });
    } else {
        console.log("Save button not found in the DOM, skipping event listener.");
    }

    const slideContainer = document.getElementById("slide-container");
    const slideList = document.getElementById("slide-list");
    const slides = Array.from(slideList ? slideList.children : []);
    const prevButton = document.getElementById("slide-left-button");
    const nextButton = document.getElementById("slide-right-button");

    // Vérification de la présence des éléments avant de manipuler
    console.log("slideContainer:", slideContainer);
    console.log("slideList:", slideList);
    console.log("prevButton:", prevButton);
    console.log("nextButton:", nextButton);

    // Vérifier que tous les éléments nécessaires sont trouvés
    if (!slideContainer || !slideList || !prevButton || !nextButton) {
        console.error("Carousel elements not found in the DOM");
        return; // Arrêter l'exécution si les éléments sont introuvables
    }

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoSlideInterval; // Déclare l'intervalle pour le défilement automatique

    function updateSlidePosition() {
        const slideWidth = slideContainer.clientWidth; // Largeur d'une image
        slideList.style.transform = `translateX(-${currentIndex * slideWidth}px)`; // Déplace la liste des slides
        console.log(
            "Updating position to:",
            `translateX(-${currentIndex * slideWidth}px)`
        );
    }

    // Fonction pour faire défiler automatiquement
    function autoSlide() {
        // Utilisation du modulo pour revenir à la première diapositive après la dernière
        currentIndex = (currentIndex + 1) % totalSlides; // Si currentIndex devient égal à totalSlides, cela le ramène à 0
        updateSlidePosition();
    }

    // Démarrer l'intervalle de défilement automatique
    function startAutoSlide() {
        autoSlideInterval = setInterval(autoSlide, 3000); // Intervalle de 3 secondes
    }

    // Arrêter l'intervalle de défilement automatique
    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    // Démarrer l'auto défilement au chargement
    startAutoSlide();

    // Bouton gauche
    prevButton.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlidePosition();
        }
        stopAutoSlide(); // Arrêter l'auto défilement quand l'utilisateur clique
        startAutoSlide(); // Redémarrer l'auto défilement après le clic
    });

    // Bouton droit
    nextButton.addEventListener("click", () => {
        if (currentIndex < slides.length - 1) {
            currentIndex++;
            updateSlidePosition();
        }
        stopAutoSlide(); // Arrêter l'auto défilement quand l'utilisateur clique
        startAutoSlide(); // Redémarrer l'auto défilement après le clic
    });

    // Réajuster sur redimensionnement
    window.addEventListener("resize", () => {
        console.log("Window resized. Recalculating slide position...");
        updateSlidePosition();
    });

    // Initialiser la position au chargement
    updateSlidePosition();
});
