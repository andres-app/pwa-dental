const CACHE_NAME = 'dental-pwa-shell-v35';

const APP_SHELL = [
    './',
    './index.php',
    './assets/css/app.css',
    './assets/js/app.js',
    './assets/js/push.js',
    './manifest.json',
    './assets/img/icon-192.png',
    './assets/img/icon-512.png'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function (cache) {
                return cache.addAll(APP_SHELL);
            })
            .catch(function () {
                return null;
            })
    );

    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(
                keys
                    .filter(function (key) {
                        return key !== CACHE_NAME;
                    })
                    .map(function (key) {
                        return caches.delete(key);
                    })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    if (event.request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(event.request.url);

    if (
        requestUrl.pathname.includes('/api/') ||
        requestUrl.pathname.includes('/vendor/')
    ) {
        event.respondWith(fetch(event.request));
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(function (response) {
                if (!response || response.status !== 200) {
                    return response;
                }

                const responseClone = response.clone();

                caches.open(CACHE_NAME).then(function (cache) {
                    cache.put(event.request, responseClone);
                });

                return response;
            })
            .catch(function () {
                return caches.match(event.request).then(function (cached) {
                    if (cached) {
                        return cached;
                    }

                    if (event.request.mode === 'navigate') {
                        return caches.match('./index.php');
                    }

                    return null;
                });
            })
    );
});

self.addEventListener('push', function (event) {
    let data = {
        title: 'Dental App',
        body: 'Tienes una nueva notificación.',
        url: './index.php',
        icon: './assets/img/icon-192.png',
        badge: './assets/img/icon-192.png'
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

    const notificationTitle = data.title;

    const notificationOptions = {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        vibrate: [100, 50, 100],
        tag: 'dental-app-notification',
        renotify: true,
        data: {
            url: data.url
        }
    };

    event.waitUntil(
        self.registration.showNotification(notificationTitle, notificationOptions)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    let targetUrl = './index.php';

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