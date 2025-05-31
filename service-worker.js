const CACHE_NAME = "raven-hunter-v1";

// Cache all files under /public by using a wildcard fetch strategy
self.addEventListener("install", (event) => {
    // No need to pre-cache specific files, we'll cache on fetch
    self.skipWaiting();
});

// Fetch event to serve cached assets or fetch from network and cache them
self.addEventListener("fetch", (event) => {
    // Only handle requests to /public
    if (event.request.url.includes("/public/")) {
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(event.request).then((response) => {
                    if (response) {
                        return response;
                    }
                    return fetch(event.request).then((networkResponse) => {
                        cache.put(event.request, networkResponse.clone());
                        return networkResponse;
                    });
                });
            })
        );
    }
});