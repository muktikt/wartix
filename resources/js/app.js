import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import './echo';
import { initGlobalConfirmInterceptors, showConfirmPopup, showToast } from './swal';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// Initialize modern SweetAlert confirm popups interceptor
initGlobalConfirmInterceptors();


/**
 * Scroll-triggered animations via Intersection Observer.
 * Supports multiple animation types:
 *   - `.reveal-on-scroll`  : fade/slide up into view
 *   - `.scroll-animate`    : legacy scroll animations
 *   - `.reveal-drop`       : drop from above with bounce easing
 *   - `.stagger-children`  : container whose `.stagger-item` children
 *                            drop in one-by-one with auto delays
 *
 * Stagger delays can be added with `data-delay="<ms>"`.
 */
const initScrollReveal = () => {
    // Add js-loaded class to body to enable scroll animation styles safely
    document.body.classList.add('js-loaded');

    const allTargets = '.reveal-on-scroll, .scroll-animate, .reveal-drop, .stagger-children';

    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll(allTargets).forEach((el) => {
            el.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const delay = entry.target.dataset.delay || 0;
                    setTimeout(() => {
                        entry.target.classList.add('is-visible');
                    }, Number(delay));
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.05, rootMargin: '0px 0px -30px 0px' }
    );

    document.querySelectorAll(allTargets).forEach((el) => {
        observer.observe(el);
    });
};

if (document.readyState === 'interactive' || document.readyState === 'complete') {
    initScrollReveal();
} else {
    document.addEventListener('DOMContentLoaded', initScrollReveal);
}