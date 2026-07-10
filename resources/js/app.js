import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(collapse);
Alpine.plugin(intersect);

/**
 * Reusable "count up" component for animating numbers into view.
 * Usage:
 *   <span x-data="counter(1234)" x-intersect.once="start()" x-text="display"></span>
 *
 * `target` can be an integer or a float (decimals are preserved).
 * `duration` is in ms.
 */
Alpine.data('counter', (target = 0, duration = 900) => ({
    display: 0,
    started: false,
    start() {
        if (this.started) return;
        this.started = true;

        // Respect users who prefer reduced motion — just show the final value.
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.display = target;
            return;
        }

        const isFloat = target % 1 !== 0;
        const startTime = performance.now();

        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            // easeOutCubic — quick start, gentle settle. Feels elegant rather than mechanical.
            const eased = 1 - Math.pow(1 - progress, 3);
            const value = eased * target;
            this.display = isFloat ? Number(value.toFixed(1)) : Math.floor(value);

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                this.display = target;
            }
        };

        requestAnimationFrame(step);
    },
}));

window.Alpine = Alpine;
Alpine.start();

// Bootstrap (axios defaults) and realtime (Echo/Reverb) are loaded *after*
// Alpine has already started, and wrapped so that if either fails — e.g. a
// missing VITE_REVERB_APP_KEY in production — it can never take down Alpine,
// animations, or any other UI interactivity on the page.
import('./bootstrap').catch((e) => console.warn('bootstrap.js failed to load:', e));
