let currentSlide = 0;

function showSlides(n) {
    let slides = document.getElementsByClassName("carousel-item");
    if (n >= slides.length) {
        currentSlide = 0;
    }
    if (n < 0) {
        currentSlide = slides.length - 1;
    }
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[currentSlide].style.display = "block";
}

function plusSlides(n) {
    showSlides(currentSlide += n);
}

// Initialize the first slide
showSlides(currentSlide);
