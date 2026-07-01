import './bootstrap';

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {
            // L'échec d'enregistrement du service worker ne doit jamais bloquer l'application.
        });
    });
}
