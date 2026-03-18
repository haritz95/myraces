const CACHE_VERSION = 'myraces-v1';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const PAGES_CACHE  = `${CACHE_VERSION}-pages`;

const PRECACHE_URLS = [
    '/offline',
];

const STATIC_EXTENSIONS = ['.css', '.js', '.woff', '.woff2', '.ttf', '.svg', '.png', '.jpg', '.webp', '.ico'];

// ── Install ──────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => cache.addAll(PRECACHE_URLS).catch(() => {}))
            .then(() => self.skipWaiting())
    );
});

// ── Activate: clean old caches ────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key.startsWith('myraces-') && key !== STATIC_CACHE && key !== PAGES_CACHE)
                    .map((key) => caches.delete(key))
            )
        ).then(() => self.clients.claim())
    );
});

// ── Fetch strategy ────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET, cross-origin, and admin requests
    if (request.method !== 'GET') { return; }
    if (url.origin !== self.location.origin) { return; }
    if (url.pathname.startsWith('/admin')) { return; }

    const isStatic = STATIC_EXTENSIONS.some((ext) => url.pathname.includes(ext));
    const isPage   = request.headers.get('Accept')?.includes('text/html');

    if (isStatic) {
        // Cache-first for static assets
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) { return cached; }
                return fetch(request).then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(STATIC_CACHE).then((c) => c.put(request, clone));
                    }
                    return response;
                });
            })
        );
    } else if (isPage) {
        // Network-first for HTML pages, cache as fallback
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(PAGES_CACHE).then((c) => c.put(request, clone));
                    }
                    return response;
                })
                .catch(() =>
                    caches.match(request).then((cached) => cached || caches.match('/offline'))
                )
        );
    }
});
