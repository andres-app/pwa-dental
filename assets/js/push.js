(function () {
    'use strict';

    const baseUrl = window.APP_BASE_URL || '/';

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; i++) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    }

    async function getPushConfig() {
        const response = await fetch(baseUrl + 'api/push_config.php', {
            cache: 'no-store'
        });

        if (!response.ok) {
            throw new Error('No se pudo obtener la llave pública push.');
        }

        return await response.json();
    }

    async function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            throw new Error('Este navegador no soporta Service Worker.');
        }

        const registration = await navigator.serviceWorker.register(baseUrl + 'service-worker.js', {
            scope: baseUrl,
            updateViaCache: 'none'
        });

        await registration.update();

        return await navigator.serviceWorker.ready;
    }

    async function showLocalNotification(registration) {
        await registration.showNotification('Dental App', {
            body: 'Prueba local: el Service Worker puede mostrar notificaciones.',
            icon: baseUrl + 'assets/img/icon-192.png',
            badge: baseUrl + 'assets/img/icon-192.png',
            vibrate: [100, 50, 100],
            tag: 'dental-app-local-test',
            renotify: true,
            data: {
                url: baseUrl + 'index.php'
            }
        });
    }

    async function enablePushNotifications() {
        if (!('Notification' in window)) {
            throw new Error('Este navegador no soporta notificaciones.');
        }

        if (!('PushManager' in window)) {
            throw new Error('Este navegador no soporta Push API.');
        }

        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            throw new Error('El permiso de notificaciones fue denegado.');
        }

        const registration = await registerServiceWorker();

        await showLocalNotification(registration);

        const config = await getPushConfig();

        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(config.publicKey)
            });
        }

        const response = await fetch(baseUrl + 'api/push_subscribe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(subscription)
        });

        if (!response.ok) {
            throw new Error('No se pudo guardar la suscripción push.');
        }

        return true;
    }

    async function sendTestNotification(button) {
        const originalHtml = button ? button.innerHTML : '';

        try {
            if (button) {
                button.disabled = true;
                button.innerHTML = '...';
            }

            await enablePushNotifications();

            await new Promise(function (resolve) {
                setTimeout(resolve, 1200);
            });

            const response = await fetch(baseUrl + 'api/push_test.php', {
                method: 'POST',
                cache: 'no-store'
            });

            const result = await response.json();

            if (!response.ok || !result.ok) {
                console.error(result);
                throw new Error(result.message || 'No se pudo enviar la notificación push.');
            }

            alert('Prueba enviada. Primero debió salir una notificación local y luego el push real.');
        } catch (error) {
            console.error(error);
            alert(error.message || 'Error al probar notificaciones push.');
        } finally {
            if (button) {
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('[data-push-test]');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                sendTestNotification(button);
            });
        });
    });
})();