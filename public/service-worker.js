const CACHE_NAME = 'ceimo-cache-v1';
const OFFLINE_URL = '/';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
        ))
    );
    self.clients.claim();
});

// Stratégie "network first, fallback cache" pour les pages HTML,
// afin de ne jamais servir de contenu obsolète pour les données
// sensibles (paiement, quiz) tout en offrant une continuité hors-ligne
// basique pour les pages déjà visitées.
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                const copy = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));

                return response;
            })
            .catch(() => caches.match(event.request).then((cached) => cached || caches.match(OFFLINE_URL)))
    );
});
