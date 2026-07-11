import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import './echo';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

/**
 * Scroll-triggered animations via Intersection Observer.
 * Elements with the class `.scroll-animate` will fade/slide into view
 * once they enter the viewport. Stagger delays can be added with
 * `data-delay="<ms>"` or the `.anim-delay-*` utility classes.
 */
const initScrollAnimations = () => {
    // If browser doesn't support IntersectionObserver, show all elements immediately
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.scroll-animate').forEach((el) => {
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
        { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
    );

    document.querySelectorAll('.scroll-animate').forEach((el) => {
        observer.observe(el);
    });
};

if (document.readyState === 'interactive' || document.readyState === 'complete') {
    initScrollAnimations();
} else {
    document.addEventListener('DOMContentLoaded', initScrollAnimations);
}