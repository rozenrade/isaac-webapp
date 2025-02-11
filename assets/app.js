import "./styles/app.css";

// Saving button
document.addEventListener("DOMContentLoaded", () => {
    const menuBtn = document.getElementById("menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.toggle("max-h-0"); 
        mobileMenu.classList.toggle("max-h-[500px]"); 
    });

    // Gestion du carrousel
    const slideContainer = document.getElementById("slide-container");
    const slideList = document.getElementById("slide-list");
    const slides = Array.from(slideList ? slideList.children : []);
    const prevButton = document.getElementById("slide-left-button");
    const nextButton = document.getElementById("slide-right-button");

    // Eearly return
    if (!slideContainer || !slideList || !prevButton || !nextButton) {
        return;
    }

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoSlideInterval;

    function updateSlidePosition() {
        const slideWidth = slideContainer.clientWidth;
        slideList.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    }

    function autoSlide() {
        currentIndex = (currentIndex + 1) % totalSlides; 
        updateSlidePosition();
    }

    function startAutoSlide() {
        autoSlideInterval = setInterval(autoSlide, 3000); 
    }

    function stopAutoSlide() {
        clearInterval(autoSlideInterval);
    }

    startAutoSlide();

    prevButton.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = slides.length - 1;
        }
        stopAutoSlide(); 
        startAutoSlide(); 
        updateSlidePosition();
    });

    // Bouton droit
    nextButton.addEventListener("click", () => {
        if (currentIndex < slides.length - 1) {
            currentIndex++;
        } else {
            currentIndex = 0; 
        }
        stopAutoSlide(); 
        startAutoSlide(); 
        updateSlidePosition();
    });

    // Réajuster sur redimensionnement
    window.addEventListener("resize", () => {
        updateSlidePosition();
    });

    // Initialiser la position au chargement
    updateSlidePosition();
});
