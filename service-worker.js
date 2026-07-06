const CACHE_NAME = 'dental-pwa-shell-v93';

const STATIC_ASSETS = [
    '/manifest.json',
    '/assets/css/app.css',
    '/assets/js/app.js',
    '/assets/js/push.js',
    '/assets/img/icon-192.png',
    '/assets/img/icon-512.png'
];

async function safePrecache() {
    const cache = await caches.open(CACHE_NAME);

    await Promise.allSettled(
        STATIC_ASSETS.map(async function (asset) {
            try {
                const response = await fetch(asset, {
                    cache: 'reload',
                    credentials: 'same-origin'
                });

                if (
                    response &&
                    response.ok &&
                    !response.redirected &&
                    response.type === 'basic'
                ) {
                    await cache.put(asset, response.clone());
                }
            } catch (error) {
                return null;
            }
        })
    );
}

self.addEventListener('install', function (event) {
    event.waitUntil(safePrecache());
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys()
            .then(function (keys) {
                return Promise.all(
                    keys
                        .filter(function (key) {
                            return key !== CACHE_NAME;
                        })
                        .map(function (key) {
                            return caches.delete(key);
                        })
                );
            })
            .then(function () {
                return safePrecache();
            })
            .then(function () {
                return self.clients.claim();
            })
    );
});

self.addEventListener('fetch', function (event) {
    const request = event.request;

    if (request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(request.url);

    if (requestUrl.origin !== self.location.origin) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | No interceptar páginas, login, PHP ni APIs
    |--------------------------------------------------------------------------
    */
    if (
        request.mode === 'navigate' ||
        request.destination === 'document' ||
        requestUrl.pathname === '/' ||
        requestUrl.pathname.endsWith('.php') ||
        requestUrl.pathname.includes('/auth/') ||
        requestUrl.pathname.includes('/api/') ||
        requestUrl.pathname.includes('/admin/') ||
        requestUrl.pathname.includes('/vendor/')
    ) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Solo manejar archivos estáticos
    |--------------------------------------------------------------------------
    */
    event.respondWith(
        caches.match(request).then(function (cached) {
            return fetch(request)
                .then(function (response) {
                    if (
                        response &&
                        response.status === 200 &&
                        !response.redirected &&
                        response.type === 'basic'
                    ) {
                        const responseClone = response.clone();

                        caches.open(CACHE_NAME).then(function (cache) {
                            cache.put(request, responseClone);
                        });
                    }

                    return response;
                })
                .catch(function () {
                    if (cached) {
                        return cached;
                    }

                    return new Response('', {
                        status: 504,
                        statusText: 'Offline'
                    });
                });
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