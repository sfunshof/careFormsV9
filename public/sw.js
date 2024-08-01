const CACHE_VERSION = 'v2'; // Update the cache version
const CACHE_NAME = `offline-${CACHE_VERSION}`;
const FILES_TO_CACHE = [
    '/',
    '/offline.html'
];

// Preload function to cache essential files
const preLoad = function () {
    return caches.open(CACHE_NAME).then(function (cache) {
        return cache.addAll(FILES_TO_CACHE);
    });
};

// On install, preload essential files and take control immediately
self.addEventListener('install', function (event) {
    event.waitUntil(
        preLoad().then(() => self.skipWaiting())
    );
});

// On activate, clear old caches
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

// Check response and add to cache if necessary
const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    if (!request.url.startsWith('http')) {
        console.warn('Skipping caching for request with unsupported scheme:', request.url);
        return;
    }

    return caches.open(CACHE_NAME).then(function (cache) {
        return fetch(request).then(function (response) {
            return cache.put(request, response);
        });
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
    event.respondWith(
        checkResponse(event.request).catch(function () {
            return returnFromCache(event.request);
        })
    );
    if (event.request.url.startsWith('http')) {
        event.waitUntil(addToCache(event.request));
    }
});
