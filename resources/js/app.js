import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import './echo';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/**
 * Scroll-triggered animations via Intersection Observer.
 * Elements with the class `.reveal-on-scroll` will fade/slide into view
 * once they enter the viewport. Stagger delays can be added with
 * `data-delay="<ms>"`.
 */
const initScrollReveal = () => {
    // Add js-loaded class to body to enable scroll animation styles safely
    document.body.classList.add('js-loaded');

    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
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

    document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
        observer.observe(el);
    });
};

if (document.readyState === 'interactive' || document.readyState === 'complete') {
    initScrollReveal();
} else {
    document.addEventListener('DOMContentLoaded', initScrollReveal);
}