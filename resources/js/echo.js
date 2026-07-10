import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Realtime (Reverb) is optional — if the app key isn't configured, skip it
// instead of throwing. A missing key here should never take down the rest
// of the JS bundle (Alpine, animations, forms, etc).
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    try {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbKey,
            wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
            enabledTransports: ['ws', 'wss'],
        });
    } catch (e) {
        console.warn('Realtime (Reverb) failed to initialize:', e);
    }
} else {
    console.warn('VITE_REVERB_APP_KEY is not set — realtime features are disabled.');
}