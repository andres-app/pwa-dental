const CACHE_NAME = 'dental-pwa-shell-v52';

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
    | 1. APIs y otros dominios: siempre directo a red
    |--------------------------------------------------------------------------
    | Ejemplo:
    | https://app.ortodonciaclinica.pe/api/...
    */
    if (requestUrl.origin !== self.location.origin) {
        event.respondWith(fetch(event.request));
        return;
    }

    if (
        requestUrl.pathname.includes('/api/') ||
        requestUrl.pathname.includes('/vendor/')
    ) {
        event.respondWith(fetch(event.request));
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Páginas PHP: nunca cachear
    |--------------------------------------------------------------------------
    | Esto evita el error:
    | "Response served by service worker has redirections"
    */
    if (
        event.request.mode === 'navigate' ||
        requestUrl.pathname.endsWith('.php') ||
        requestUrl.pathname === '/'
    ) {
        event.respondWith(
            fetch(event.request, {
                cache: 'no-store',
                redirect: 'follow'
            }).catch(function () {
                return new Response(
                    '<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sin conexión</title></head><body style="font-family:system-ui;padding:24px;"><h1>Sin conexión</h1><p>No se pudo cargar esta pantalla. Verifica tu internet e intenta nuevamente.</p></body></html>',
                    {
                        status: 503,
                        headers: {
                            'Content-Type': 'text/html; charset=utf-8'
                        }
                    }
                );
            })
        );
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Archivos estáticos: cache first
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
                return null;
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