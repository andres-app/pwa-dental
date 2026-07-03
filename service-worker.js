const CACHE_NAME = 'dental-pwa-shell-v58';

const STATIC_ASSETS = [
    '/manifest.json',
    '/assets/css/app.css',
    '/assets/js/app.js',
    '/assets/js/push.js',
    '/assets/img/icon-192.png',
    '/assets/img/icon-512.png'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function (cache) {
                return cache.addAll(STATIC_ASSETS);
            })
            .catch(function () {
                return null;
            })
    );

    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys()
            .then(function (keys) {
                return Promise.all(
                    keys.map(function (key) {
                        return caches.delete(key);
                    })
                );
            })
            .then(function () {
                return caches.open(CACHE_NAME);
            })
            .then(function (cache) {
                return cache.addAll(STATIC_ASSETS);
            })
            .then(function () {
                return self.clients.claim();
            })
    );
});

self.addEventListener('fetch', function (event) {
    if (event.request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(event.request.url);

    /*
    |--------------------------------------------------------------------------
    | MUY IMPORTANTE PARA SAFARI / iOS
    |--------------------------------------------------------------------------
    | No interceptamos navegación ni páginas PHP.
    | Así evitamos:
    | "response served by service worker has redirections"
    */
    if (
        event.request.mode === 'navigate' ||
        event.request.destination === 'document' ||
        requestUrl.pathname === '/' ||
        requestUrl.pathname.endsWith('.php')
    ) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | No tocar APIs ni otros dominios
    |--------------------------------------------------------------------------
    */
    if (requestUrl.origin !== self.location.origin) {
        return;
    }

    if (
        requestUrl.pathname.includes('/api/') ||
        requestUrl.pathname.includes('/vendor/')
    ) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Solo cachear archivos estáticos
    |--------------------------------------------------------------------------
    */
    event.respondWith(
        caches.match(event.request)
            .then(function (cached) {
                if (cached) {
                    return cached;
                }

                return fetch(event.request).then(function (response) {
                    if (
                        !response ||
                        response.status !== 200 ||
                        response.redirected ||
                        response.type !== 'basic'
                    ) {
                        return response;
                    }

                    const responseClone = response.clone();

                    caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(event.request, responseClone);
                    });

                    return response;
                });
            })
            .catch(function () {
                return fetch(event.request);
            })
    );
});

self.addEventListener('push', function (event) {
    let data = {
        title: 'Dental App',
        body: 'Tienes una nueva notificación.',
        url: '/index.php',
        icon: '/assets/img/icon-192.png',
        badge: '/assets/img/icon-192.png'
    };

    if (event.data) {
        try {
            const pushData = event.data.json();

            data = {
                title: pushData.title || data.title,
                body: pushData.body || data.body,
                url: pushData.url || data.url,
                icon: pushData.icon || data.icon,
                badge: pushData.badge || data.badge
            };
        } catch (error) {
            data.body = event.data.text();
        }
    }

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: data.icon,
            badge: data.badge,
            vibrate: [100, 50, 100],
            tag: 'dental-app-notification',
            renotify: true,
            data: {
                url: data.url
            }
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    let targetUrl = '/index.php';

    if (event.notification.data && event.notification.data.url) {
        targetUrl = event.notification.data.url;
    }

    const finalUrl = new URL(targetUrl, self.registration.scope).href;

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url && 'focus' in client) {
                    client.navigate(finalUrl);
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(finalUrl);
            }

            return null;
        })
    );
});