<?php
// auth/login.php
define('PWA_PUBLIC_PAGE', true);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/icons.php';

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

    <style>
        .login-screen {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at top left, rgba(14, 165, 183, .20), transparent 34%),
                radial-gradient(circle at bottom right, rgba(7, 59, 70, .14), transparent 38%),
                #f4f8fb;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 30px;
            padding: 28px;
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(15, 23, 42, .08);
            box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
            backdrop-filter: blur(18px);
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .login-brand__icon {
            width: 58px;
            height: 58px;
            border-radius: 22px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, <?= e(APP_THEME_COLOR) ?>, <?= e(APP_THEME_DARK) ?>);
            box-shadow: 0 16px 30px rgba(14, 165, 183, .28);
        }

        .login-brand__text strong {
            display: block;
            font-size: 22px;
            line-height: 1.1;
            color: #0f172a;
        }

        .login-brand__text small {
            display: block;
            margin-top: 4px;
            color: #64748b;
            font-size: 13px;
        }

        .login-card h1 {
            margin: 0;
            color: #0f172a;
            font-size: 26px;
            letter-spacing: -.03em;
        }

        .login-card .login-subtitle {
            margin: 8px 0 0;
            color: #64748b;
            font-size: 15px;
            line-height: 1.5;
        }

        .login-form {
            display: grid;
            gap: 14px;
            margin-top: 22px;
        }

        .login-field {
            display: grid;
            gap: 8px;
        }

        .login-field span {
            color: #334155;
            font-size: 13px;
            font-weight: 700;
        }

        .login-field .search-box {
            margin: 0;
        }

        .login-action {
            margin-top: 4px;
            min-height: 48px;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-action[disabled] {
            opacity: .82;
            cursor: wait;
        }

        .login-action__content {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .login-spinner {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 2px solid rgba(14, 165, 183, .25);
            border-top-color: <?= e(APP_THEME_COLOR) ?>;
            display: none;
            animation: loginSpin .8s linear infinite;
        }

        .login-action.is-loading .login-spinner {
            display: inline-block;
        }

        @keyframes loginSpin {
            to {
                transform: rotate(360deg);
            }
        }

        .login-status {
            display: none;
            margin: 0;
            padding: 12px 14px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.4;
            border: 1px solid transparent;
        }

        .login-status.is-info {
            display: block;
            color: #075985;
            background: rgba(14, 165, 183, .10);
            border-color: rgba(14, 165, 183, .22);
        }

        .login-status.is-success {
            display: block;
            color: #166534;
            background: rgba(34, 197, 94, .10);
            border-color: rgba(34, 197, 94, .22);
        }

        .login-status.is-error {
            display: block;
            color: #b91c1c;
            background: rgba(239, 68, 68, .10);
            border-color: rgba(239, 68, 68, .22);
        }

        .login-version {
            display: flex;
            justify-content: center;
            margin-top: 18px;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .02em;
        }

        .login-version span {
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(15, 23, 42, .06);
        }
    </style>
</head>

<body class="app-body" data-page="login">

<main class="login-screen">
    <section class="login-card">
        <div class="login-brand">
            <div class="login-brand__icon">
                <?= appIcon('tooth') ?>
            </div>

            <div class="login-brand__text">
                <strong><?= e(APP_NAME) ?></strong>
                <small>Gestión odontológica móvil</small>
            </div>
        </div>

        <h1>Bienvenido</h1>
        <p class="login-subtitle">
            Ingresa con tus credenciales para acceder a tus citas, pacientes y recordatorios.
        </p>

        <form id="formLoginPwa" class="login-form" method="post" novalidate>
            <label class="login-field">
                <span>Usuario</span>
                <div class="search-box">
                    <input type="text" id="usuario" placeholder="Ingresa tu usuario" autocomplete="username" required>
                </div>
            </label>

            <label class="login-field">
                <span>Contraseña</span>
                <div class="search-box">
                    <input type="password" id="clave" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                </div>
            </label>

            <button type="submit" class="btn btn-soft login-action" id="btnLogin">
                <span class="login-action__content">
                    <span class="login-spinner" aria-hidden="true"></span>
                    <span id="btnLoginText">Ingresar</span>
                </span>
            </button>

            <p id="loginMensaje" class="login-status" aria-live="polite"></p>
        </form>

        <div class="login-version">
            <span>Versión <?= e(APP_VERSION) ?> · PWA</span>
        </div>
    </section>
</main>

<script>
(function () {
    'use strict';

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js?v=55', {
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
    const btnLogin = document.getElementById('btnLogin');
    const btnLoginText = document.getElementById('btnLoginText');

    const API_LOGIN = 'https://app.ortodonciaclinica.pe/api/auth/login.php';

    function setEstado(tipo, texto) {
        mensaje.className = 'login-status';

        if (tipo) {
            mensaje.classList.add('is-' + tipo);
        }

        mensaje.textContent = texto || '';
    }

    function setCargando(cargando) {
        btnLogin.disabled = cargando;
        usuario.disabled = cargando;
        clave.disabled = cargando;

        btnLogin.classList.toggle('is-loading', cargando);
        btnLoginText.textContent = cargando ? 'Ingresando...' : 'Ingresar';
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const usuarioValor = usuario.value.trim();
        const claveValor = clave.value;

        if (!usuarioValor || !claveValor) {
            setEstado('error', 'Completa tu usuario y contraseña para continuar.');
            return;
        }

        setCargando(true);
        setEstado('info', 'Verificando tus credenciales de forma segura...');

        try {
            const response = await fetch(API_LOGIN, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    usuario: usuarioValor,
                    clave: claveValor
                })
            });

            const texto = await response.text();

            let json;

            try {
                json = JSON.parse(texto);
            } catch (e) {
                console.error(texto);
                throw new Error('El servidor no devolvió una respuesta válida.');
            }

            if (!response.ok || !json.ok) {
                throw new Error(json.mensaje || json.error || 'No se pudo iniciar sesión.');
            }

            setEstado('success', 'Acceso confirmado. Preparando tu espacio de trabajo...');
            btnLoginText.textContent = 'Acceso confirmado';

            window.setTimeout(function () {
                window.location.replace('/pages/citas.php');
            }, 650);

        } catch (error) {
            setCargando(false);
            setEstado('error', error.message || 'No se pudo iniciar sesión.');
        }
    });
})();
</script>

</body>
</html>