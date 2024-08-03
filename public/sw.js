const CACHE_VERSION = 'v2';
const CACHE_NAME = `offline-${CACHE_VERSION}`;
const FILES_TO_CACHE = [
    '/',
    '/offline.html'
];

const preLoad = function () {
    return caches.open(CACHE_NAME).then(function (cache) {
        return cache.addAll(FILES_TO_CACHE);
    });
};

self.addEventListener('install', function (event) {
    event.waitUntil(
        preLoad().then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', function (event) {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (!cacheWhitelist.includes(cacheName)) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

const checkResponse = function (request) {
    return fetch(request.clone()).then(function (response) {
        if (response.status !== 404) {
            return response;
        } else {
            throw new Error('Not found');
        }
    });
};

const addToCache = function (request, response) {
    if (request.method !== 'GET') {
        return Promise.resolve(); // Don't cache non-GET requests
    }

    return caches.open(CACHE_NAME).then(function (cache) {
        return cache.put(request, response);
    });
};

const returnFromCache = function (request) {
    return caches.open(CACHE_NAME).then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match('offline.html');
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener('fetch', function (event) {
    if (event.request.method !== 'GET') {
        return; // Don't handle non-GET requests in the service worker
    }

    event.respondWith(
        checkResponse(event.request.clone())
            .then(function(response) {
                event.waitUntil(addToCache(event.request.clone(), response.clone()));
                return response;
            })
            .catch(function () {
                return returnFromCache(event.request);
            })
    );
});