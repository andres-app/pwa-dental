<?php
define('PWA_PUBLIC_PAGE', true);

require_once __DIR__ . '/../config/app.php';

$pageTitle = 'Iniciar sesión';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <meta name="theme-color" content="<?= e(APP_THEME_COLOR) ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= e(APP_NAME) ?>">

    <title><?= e($pageTitle) ?> · <?= e(APP_NAME) ?></title>

    <link rel="manifest" href="<?= e(appUrl('manifest.json')) ?>">
    <link rel="apple-touch-icon" href="<?= e(appUrl('assets/img/icon-192.png')) ?>">
    <link rel="stylesheet" href="<?= e(assetUrl('assets/css/app.css')) ?>">
</head>

<body class="app-body" data-page="login">

<main class="app-main" style="min-height:100vh;display:grid;place-items:center;padding:24px;">
    <section class="page-title-card" style="width:100%;max-width:420px;">
        <h1>Iniciar sesión</h1>
        <p>Accede con tu usuario y contraseña para cargar tus citas en la PWA.</p>

        <form id="formLoginPwa" method="post" novalidate style="display:grid;gap:14px;margin-top:20px;">
            <label class="search-box">
                <input type="text" id="usuario" placeholder="Usuario" autocomplete="username" required>
            </label>

            <label class="search-box">
                <input type="password" id="clave" placeholder="Contraseña" autocomplete="current-password" required>
            </label>

            <button type="submit" class="btn btn-soft">
                Ingresar
            </button>

            <p id="loginMensaje" style="margin:0;color:#b91c1c;font-size:14px;"></p>
        </form>
    </section>
</main>

<script>
(function () {
    'use strict';

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js', {
                scope: '/'
            }).catch(function (error) {
                console.warn('No se pudo registrar el service worker:', error);
            });
        });
    }

    const form = document.getElementById('formLoginPwa');
    const usuario = document.getElementById('usuario');
    const clave = document.getElementById('clave');
    const mensaje = document.getElementById('loginMensaje');

    const API_LOGIN = 'https://app.ortodonciaclinica.pe/api/auth/login.php';

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        mensaje.textContent = 'Validando acceso...';

        try {
            const response = await fetch(API_LOGIN, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    usuario: usuario.value.trim(),
                    clave: clave.value
                })
            });

            const texto = await response.text();

            let json;

            try {
                json = JSON.parse(texto);
            } catch (e) {
                console.error(texto);
                throw new Error('El servidor no devolvió JSON.');
            }

            if (!response.ok || !json.ok) {
                throw new Error(json.mensaje || json.error || 'No se pudo iniciar sesión.');
            }

            /*
            Importante:
            Redirige dentro del dominio PWA.
            No mandes al usuario a app.ortodonciaclinica.pe.
            */
            window.location.replace('/pages/citas.php');

        } catch (error) {
            mensaje.textContent = error.message;
        }
    });
})();
</script>

</body>
</html>