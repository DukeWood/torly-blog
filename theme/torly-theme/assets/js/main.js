/**
 * TorlyAI Theme JavaScript
 * Granola.ai-inspired animations and interactions
 *
 * @package TorlyAI
 * @version 2.0.0
 */

(function() {
    'use strict';

    // ============================================
    // INTERSECTION OBSERVER FOR SCROLL ANIMATIONS
    // ============================================

    /**
     * Observe elements and add 'visible' class when they enter viewport
     */
    function initScrollAnimations() {
        // Check if Intersection Observer is supported
        if (!('IntersectionObserver' in window)) {
            // Fallback: add visible class to all elements immediately
            document.querySelectorAll('.feature-card, .process-step, .testimonial-card, .blog-card, .stat-item').forEach(el => {
                el.classList.add('visible');
            });
            return;
        }

        const observerOptions = {
            threshold: 0.1, // Trigger when 10% of element is visible
            rootMargin: '0px 0px -50px 0px' // Slight offset from bottom
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Optional: unobserve after animation to improve performance
                    // observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all animatable elements
        const animatableElements = document.querySelectorAll(
            '.feature-card, .process-step, .testimonial-card, .blog-card, .stat-item'
        );

        animatableElements.forEach(el => observer.observe(el));
    }

    // ============================================
    // STATS COUNTER ANIMATION
    // ============================================

    /**
     * Animate numbers counting up when stats section enters viewport
     */
    function initStatsCounter() {
        const stats = document.querySelectorAll('.stat-number');
        if (stats.length === 0) return;

        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    animateNumber(entry.target);
                    entry.target.classList.add('counted');
                }
            });
        }, observerOptions);

        stats.forEach(stat => observer.observe(stat));
    }

    /**
     * Animate a number from 0 to its target value
     */
    function animateNumber(element) {
        const text = element.textContent;
        const hasPlus = text.includes('+');
        const hasPercent = text.includes('%');
        const hasSuffix = hasPlus || hasPercent || text.includes('hrs') || text.includes('/');

        // Extract number
        let targetValue;
        if (text.includes('/')) {
            // Handle "24/7" format
            element.textContent = text; // Don't animate, just show
            return;
        } else if (text.includes('hrs')) {
            targetValue = parseInt(text.replace('hrs', ''));
        } else {
            targetValue = parseInt(text.replace(/[^0-9]/g, ''));
        }

        if (isNaN(targetValue)) return;

        const duration = 2000; // 2 seconds
        const frameDuration = 1000 / 60; // 60 FPS
        const totalFrames = Math.round(duration / frameDuration);
        let frame = 0;

        const counter = setInterval(() => {
            frame++;
            const progress = frame / totalFrames;
            const currentValue = Math.round(targetValue * easeOutQuad(progress));

            let displayText = currentValue.toLocaleString();
            if (hasPercent) displayText += '%';
            else if (hasPlus) displayText += '+';
            else if (text.includes('hrs')) displayText += 'hrs';

            element.textContent = displayText;

            if (frame === totalFrames) {
                clearInterval(counter);
                element.textContent = text; // Set final value
            }
        }, frameDuration);
    }

    /**
     * Easing function for smooth animation
     */
    function easeOutQuad(t) {
        return t * (2 - t);
    }

    // ============================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================

    /**
     * Add smooth scrolling to anchor links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');

                // Ignore if just "#"
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const headerOffset = 80; // Account for sticky header
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // ============================================
    // HEADER SCROLL BEHAVIOR
    // ============================================

    /**
     * Add shadow to header on scroll
     */
    function initHeaderScroll() {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 50) {
                header.style.boxShadow = '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)';
            } else {
                header.style.boxShadow = 'none';
            }

            lastScroll = currentScroll;
        });
    }

    // ============================================
    // LAZY LOAD IMAGES (optional enhancement)
    // ============================================

    /**
     * Lazy load images as they enter viewport
     */
    function initLazyLoad() {
        if (!('IntersectionObserver' in window)) return;

        const images = document.querySelectorAll('img[data-src]');
        if (images.length === 0) return;

        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // ============================================
    // PARALLAX EFFECT FOR HERO (subtle)
    // ============================================

    /**
     * Subtle parallax effect on hero section
     */
    function initParallax() {
        const hero = document.querySelector('.hero-section');
        if (!hero) return;

        // Only on desktop
        if (window.innerWidth < 1024) return;

        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;

            if (scrolled < window.innerHeight) {
                hero.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
            }
        });
    }

    // ============================================
    // FORM VALIDATION (if forms exist)
    // ============================================

    /**
     * Add real-time form validation
     */
    function initFormValidation() {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[required], textarea[required]');

            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    validateInput(input);
                });

                input.addEventListener('input', () => {
                    if (input.classList.contains('invalid')) {
                        validateInput(input);
                    }
                });
            });

            form.addEventListener('submit', (e) => {
                let isValid = true;

                inputs.forEach(input => {
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Validate individual input
     */
    function validateInput(input) {
        const value = input.value.trim();
        const type = input.type;
        let isValid = true;

        if (value === '') {
            isValid = false;
        } else if (type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        }

        if (isValid) {
            input.classList.remove('invalid');
            input.classList.add('valid');
        } else {
            input.classList.remove('valid');
            input.classList.add('invalid');
        }

        return isValid;
    }

    // ============================================
    // MOBILE MENU TOGGLE (if needed)
    // ============================================

    /**
     * Toggle mobile navigation menu
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('.main-navigation');

        if (!menuToggle || !nav) return;

        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
            menuToggle.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!nav.contains(e.target) && !menuToggle.contains(e.target)) {
                nav.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    }

    // ============================================
    // INITIALIZE ALL ON DOM READY
    // ============================================

    /**
     * Initialize all functionality when DOM is ready
     */
    function init() {
        initScrollAnimations();
        initStatsCounter();
        initSmoothScroll();
        initHeaderScroll();
        initLazyLoad();
        // initParallax(); // Commented out - can enable if desired
        initFormValidation();
        initMobileMenu();

        // Log initialization
        console.log('TorlyAI theme initialized - Granola.ai style 🚀');
    }

    // Run init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
