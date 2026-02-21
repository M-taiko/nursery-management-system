// ============================================
// Main JavaScript Entry Point
// ============================================

import './bootstrap';
import 'bootstrap';

// === Import Alpine.js ===
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// === Import AOS (Animate On Scroll) ===
import AOS from 'aos';
import 'aos/dist/aos.css';

// ============================================
// Alpine.js Setup
// ============================================

// Register persist plugin
Alpine.plugin(persist);

// Global Alpine store for theme management
Alpine.store('theme', {
    dark: Alpine.$persist(false).as('theme-dark'),

    toggle() {
        this.dark = !this.dark;
        this.apply();
    },

    apply() {
        document.documentElement.setAttribute('data-theme', this.dark ? 'dark' : 'light');

        // Update meta theme-color for mobile browsers
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', this.dark ? '#0f172a' : '#4e73df');
        }
    },

    init() {
        // Check if user has system dark mode preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        // If no saved preference, use system preference
        if (this.dark === null || this.dark === undefined) {
            this.dark = prefersDark;
        }

        this.apply();

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            this.dark = e.matches;
            this.apply();
        });
    }
});

// Global Alpine store for sidebar (mobile)
Alpine.store('sidebar', {
    open: false,

    toggle() {
        this.open = !this.open;
        document.body.style.overflow = this.open ? 'hidden' : '';
    },

    show() {
        this.open = true;
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.open = false;
        document.body.style.overflow = '';
    }
});

// Start Alpine.js
window.Alpine = Alpine;
Alpine.start();

// ============================================
// AOS (Animate On Scroll) Setup
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    // Initialize AOS only if there are elements with data-aos
    if (document.querySelector('[data-aos]')) {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100,
            delay: 0,
            disable: 'mobile' // Optionally disable on mobile for better performance
        });
    }

    // Refresh AOS on window resize
    window.addEventListener('resize', () => {
        AOS.refresh();
    });
});

// ============================================
// Auto-hide alerts
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 4000);
    });
});

// ============================================
// Mobile Gestures (Swipe to open/close sidebar)
// RTL: sidebar is on the RIGHT side
// ============================================

let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].clientX;
}, { passive: true });

document.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].clientX;
    handleSwipe();
}, { passive: true });

function handleSwipe() {
    const threshold = 60;
    const diff = touchStartX - touchEndX; // positive = swipe left, negative = swipe right

    // RTL: Swipe LEFT from RIGHT edge → open sidebar
    if (diff > threshold && touchStartX > window.innerWidth - 30) {
        if (window.Alpine) Alpine.store('sidebar').show();
    }

    // RTL: Swipe RIGHT when sidebar is open → close sidebar
    if (diff < -threshold && window.Alpine && Alpine.store('sidebar').open) {
        Alpine.store('sidebar').close();
    }
}

// ============================================
// Page Transition Effect + Reset body scroll on load
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    // Always reset body overflow on page load (in case sidebar was open)
    document.body.style.overflow = '';

    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.classList.add('animate-fade-in-up');
    }

    // Close sidebar when a nav link is clicked (before navigation)
    document.querySelectorAll('.sidebar .nav-link, .sidebar .btn-logout').forEach(function (link) {
        link.addEventListener('click', function () {
            document.body.style.overflow = '';
        });
    });
});

// ============================================
// Initialize Theme on Load
// ============================================

document.addEventListener('alpine:init', () => {
    Alpine.store('theme').init();
});

// ============================================
// Utility Functions
// ============================================

// Add ripple effect to buttons
document.addEventListener('click', function (e) {
    const button = e.target.closest('.btn-glass, .mobile-nav-item');

    if (button) {
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');

        button.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    }
});

// Lazy load images
document.addEventListener('DOMContentLoaded', function () {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                    }
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }
});
