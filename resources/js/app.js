import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import './echo';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/**
 * Scroll-reveal observer
 * Any element with a `data-reveal` attribute fades/slides in the first
 * time it enters the viewport. Kept intentionally lightweight (no extra
 * npm dependency) and subtle/professional in feel — see app.css for the
 * actual animation curve.
 */
function initScrollReveal() {
    const targets = document.querySelectorAll('[data-reveal]');
    if (!targets.length) return;

    if (!('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    targets.forEach((el) => observer.observe(el));
}

/**
 * Briefly highlights a row/card that was just inserted into the DOM
 * (e.g. a new realtime monitor entry) with a soft background flash.
 */
window.flashNewRow = function flashNewRow(el) {
    if (!el) return;
    el.classList.add('animate-row-highlight');
    setTimeout(() => el.classList.remove('animate-row-highlight'), 1700);
};

document.addEventListener('DOMContentLoaded', initScrollReveal);