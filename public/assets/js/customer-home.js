document.addEventListener('DOMContentLoaded', function () {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    function showSlide(index) {
        slides.forEach(function (slide) {
            slide.classList.remove('active');
        });

        dots.forEach(function (dot) {
            dot.classList.remove('active');
        });

        if (slides[index] && dots[index]) {
            slides[index].classList.add('active');
            dots[index].classList.add('active');
        }

        currentSlide = index;
    }

    window.goToSlide = function (index) {
        showSlide(index);
    };

    if (slides.length > 0) {
        showSlide(0);

        setInterval(function () {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }, 5000);
    }

    const profileDropdownBtn = document.getElementById('profileDropdownBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileDropdownBtn && profileDropdown) {
        profileDropdownBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            const isOpen = profileDropdown.classList.toggle('active');
            profileDropdownBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.user-menu')) {
                profileDropdown.classList.remove('active');
                profileDropdownBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
});