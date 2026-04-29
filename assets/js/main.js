/**
 * Casa Freddo - Main JavaScript
 * Handles navigation, animations, and UI interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            
            // Animate hamburger to X
            const spans = navToggle.querySelectorAll('span');
            navToggle.classList.toggle('active');
            
            if (navToggle.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
                spans.forEach(s => s.style.backgroundColor = '#f8f5f0');
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
                spans.forEach(s => s.style.backgroundColor = '');
            }
        });

        // Close mobile menu when a link is clicked
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
                const spans = navToggle.querySelectorAll('span');
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
                spans.forEach(s => s.style.backgroundColor = '');
            });
        });
    }

    // Favorite button toggle
    document.querySelectorAll('.product-favorite').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.toggle('active');
            this.textContent = this.classList.contains('active') ? '❤️' : '🤍';
        });
    });

    // Scroll reveal animations
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealOnScroll = () => {
        const windowHeight = window.innerHeight;
        const elementVisible = 100;

        revealElements.forEach(reveal => {
            const elementTop = reveal.getBoundingClientRect().top;
            if (elementTop < windowHeight - elementVisible) {
                reveal.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Trigger once on load

    // Menu filter functionality (if on menu page)
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');

            productCards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease forwards';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Form validation helper
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ff4757';
                } else {
                    field.style.borderColor = '';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Smooth parallax effect on hero
    const hero = document.querySelector('.hero');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const heroContent = hero.querySelector('.hero-content');
            if (heroContent && scrolled < window.innerHeight) {
                heroContent.style.transform = `translateY(${scrolled * 0.3}px)`;
                heroContent.style.opacity = 1 - (scrolled / window.innerHeight);
            }
        });
    }

    // Location modal flow
    const locationModal = document.getElementById('locationModalBackdrop');
    const locationForm = document.getElementById('locationForm');
    const locationTrigger = document.getElementById('locationTrigger');
    const skipLocation = document.getElementById('skipLocation');
    const locationStorageKey = 'cf_location_v1';

    function showLocationModal() {
        if (!locationModal) return;
        locationModal.classList.add('active');
        locationModal.setAttribute('aria-hidden', 'false');
    }

    function hideLocationModal() {
        if (!locationModal) return;
        locationModal.classList.remove('active');
        locationModal.setAttribute('aria-hidden', 'true');
    }

    function setLocationLabel(area) {
        if (locationTrigger && area) {
            locationTrigger.textContent = area;
        }
    }

    if (locationTrigger) {
        locationTrigger.addEventListener('click', showLocationModal);
    }

    if (skipLocation) {
        skipLocation.addEventListener('click', hideLocationModal);
    }

    if (locationForm) {
        locationForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(locationForm);
            const country = formData.get('country');
            const city = formData.get('city');
            const area = formData.get('area');
            if (!country || !city || !area) return;

            localStorage.setItem(locationStorageKey, JSON.stringify({ country, city, area }));
            setLocationLabel(area);

            try {
                await fetch('set_location.php', {
                    method: 'POST',
                    body: formData
                });
            } catch (_) {}

            hideLocationModal();
        });
    }

    const savedLocation = localStorage.getItem(locationStorageKey);
    if (savedLocation) {
        try {
            const parsed = JSON.parse(savedLocation);
            if (parsed && parsed.area) {
                setLocationLabel(parsed.area);
            }
        } catch (_) {}
    } else {
        setTimeout(showLocationModal, 700);
    }
});
