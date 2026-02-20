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
    },

    close() {
        this.open = false;
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
// Sidebar Toggle (Legacy support)
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Auto-hide alerts
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 4000);
    });
});

// ============================================
// Mobile Gestures (Swipe to close sidebar)
// ============================================

let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (!sidebar || !overlay) return;

    const threshold = 50;
    const diff = touchStartX - touchEndX;

    // Swipe left (close sidebar in RTL)
    if (diff > threshold && sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    }

    // Swipe right from edge (open sidebar in RTL)
    if (diff < -threshold && touchStartX < 20) {
        sidebar.classList.add('show');
        overlay.classList.add('show');
    }
}

// ============================================
// Page Transition Effect
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    const mainContent = document.querySelector('.main-content');

    if (mainContent) {
        mainContent.classList.add('animate-fade-in-up');
    }
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
