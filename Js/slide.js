document.addEventListener("DOMContentLoaded", function() {
    const myslide = document.querySelectorAll('.myslide');
    let currentSlide = 0;
    let timer;

    function autoSlide() {
        currentSlide = (currentSlide + 1) % myslide.length;
        showSlide(currentSlide);
    }

    function showSlide(n) {
        // Hide all slides
        myslide.forEach(slide => {
            slide.classList.remove('active', 'zoom-in', 'wipe-in');
            slide.querySelector('img').style.transform = ''; // Reset transformations
        });

        // Show the selected slide
        myslide[n].classList.add('active');

        // Apply animation classes based on slide index
        if (n % 2 === 0) {
            myslide[n].classList.add('zoom-in');
        } else {
            myslide[n].classList.add('wipe-in');
        }

        // Reset timer
        resetTimer();
    }

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(autoSlide, 10000); // Adjust interval as needed (10 seconds)
    }

    // Initial setup
    showSlide(currentSlide);
    resetTimer();
});
