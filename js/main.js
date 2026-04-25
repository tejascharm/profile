/**
 * Main JavaScript logic for Tejas Portfolio
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Navbar
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 2. Hamburger Mobile Menu Logic
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close menu when a link is clicked
        const navItems = document.querySelectorAll('.nav-links a');
        navItems.forEach(item => {
            item.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // 3. Scroll Reveal Animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const revealElements = document.querySelectorAll('.reveal');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target); // Once revealed, keep it.
            }
        });
    }, observerOptions);

    revealElements.forEach(el => observer.observe(el));

    // 3. Page Transition (Fade in on load)
    document.body.style.opacity = "0";
    document.body.style.transition = "opacity 0.6s ease-out";

    setTimeout(() => {
        document.body.style.opacity = "1";
    }, 100);
    // 4. Lightbox Logic
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-close">&times;</div>
        <div class="lightbox-prev">&#10094;</div>
        <img src="" alt="Enlarged view">
        <div class="lightbox-next">&#10095;</div>
    `;
    document.body.appendChild(lightbox);
    
    const lightboxImg = lightbox.querySelector('img');
    const closeBtn = lightbox.querySelector('.lightbox-close');
    const prevBtn = lightbox.querySelector('.lightbox-prev');
    const nextBtn = lightbox.querySelector('.lightbox-next');
    
    const closeLightbox = () => { lightbox.classList.remove('active'); };
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
    
    let currentImages = [];
    let currentIndex = 0;
    
    const allImages = Array.from(document.querySelectorAll('.clickable-image'));
    
    allImages.forEach((img, index) => {
        img.addEventListener('click', (e) => {
            e.preventDefault();
            currentImages = allImages;
            currentIndex = index;
            updateLightboxImage();
            lightbox.classList.add('active');
        });
    });
    
    function updateLightboxImage() {
        lightboxImg.src = currentImages[currentIndex].src;
    }
    
    prevBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (currentImages.length > 0) {
            currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
            updateLightboxImage();
        }
    });
    
    nextBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (currentImages.length > 0) {
            currentIndex = (currentIndex + 1) % currentImages.length;
            updateLightboxImage();
        }
    });
    
    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    lightbox.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; });
    lightbox.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        if (touchEndX < touchStartX - 50) nextBtn.click();
        if (touchEndX > touchStartX + 50) prevBtn.click();
    });
});
