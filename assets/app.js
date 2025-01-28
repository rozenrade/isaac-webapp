/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";

// Carousel

document.addEventListener("DOMContentLoaded", () => {
    const carouselContainer = document.getElementById("carousel-container");
    const slides = document.querySelectorAll("#carousel-container > div");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");

    let currentIndex = 0;

    const updateCarousel = () => {
        // Translate le conteneur pour afficher uniquement le slide actif
        carouselContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
    };

    prevBtn.addEventListener("click", () => {
        currentIndex = currentIndex > 0 ? currentIndex - 1 : slides.length - 1;
        updateCarousel();
    });

    nextBtn.addEventListener("click", () => {
        currentIndex = currentIndex < slides.length - 1 ? currentIndex + 1 : 0;
        updateCarousel();
    });
});

let autoScroll = setInterval(() => {
    currentIndex = currentIndex < slides.length - 1 ? currentIndex + 1 : 0;
    updateCarousel();
}, 3000);

// Arrête le défilement automatique quand la souris est sur le carrousel
carouselContainer.addEventListener("mouseenter", () =>
    clearInterval(autoScroll)
);
carouselContainer.addEventListener("mouseleave", () => {
    autoScroll = setInterval(() => {
        currentIndex = currentIndex < slides.length - 1 ? currentIndex + 1 : 0;
        updateCarousel();
    }, 3000);
});

// Saving button

document.getElementById("save-button").addEventListener("click", async () => {
    // récupérer les id des items

    const itemIds = Array.from(document.querySelectorAll(".item")).map(
        (item) => item.id
    );
    try {
        const response = await fetch("/random/save", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
