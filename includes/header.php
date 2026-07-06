<?php
// includes/header.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/icons.php';

if (!defined('PWA_PUBLIC_PAGE') || PWA_PUBLIC_PAGE !== true) {
    requiereLoginPwa();
}

$pageTitle = $pageTitle ?? APP_NAME;
$activePage = $activePage ?? 'inicio';
$pageKicker = $pageKicker ?? 'Gestión odontológica';
$bodyClass = $bodyClass ?? '';

/*
|--------------------------------------------------------------------------
| Preloader
|--------------------------------------------------------------------------
| Para evitar lag al navegar entre módulos, lo dejamos desactivado por defecto.
| Si alguna vista lo necesita, puede activar:
| $showPreloader = true;
*/
$showPreloader = $showPreloader ?? false;
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">

    <!-- Notch / barra superior limpia en iPhone -->
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?= e(APP_NAME) ?>">

    <title><?= e($pageTitle) ?> · <?= e(APP_NAME) ?></title>

    <link rel="manifest" href="<?= e(appUrl('manifest.json')) ?>">
    <link rel="apple-touch-icon" href="<?= e(appUrl('assets/img/icon-192.png')) ?>">
    <link rel="stylesheet" href="<?= e(assetUrl('assets/css/app.css')) ?>?v=<?= e(APP_VERSION) ?>">
</head>

<body class="app-body <?= e($bodyClass) ?>" data-page="<?= e($activePage) ?>">

    <?php if ($showPreloader): ?>
        <div id="appPreloader" class="app-preloader" aria-label="Cargando aplicación">
            <div class="preloader-card">
                <div class="preloader-icon"><?= appIcon('tooth') ?></div>
                <div class="preloader-title">Cargando Dental App</div>
                <div class="preloader-bar"><span></span></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="app-shell">
        <header class="app-header">
            <div class="app-header__inner">

                <!-- Espacio reservado del menú eliminado.
                     Mantiene la posición actual de la marca y la campana. -->
                <button
                    class="icon-button"
                    type="button"
                    aria-hidden="true"
                    tabindex="-1"
                    disabled
                    style="visibility: hidden; pointer-events: none;">
                </button>

                <a class="brand" href="<?= e(appUrl('pages/inicio.php')) ?>" aria-label="Ir al inicio">
                    <span class="brand__mark"><?= appIcon('tooth') ?></span>
                    <span class="brand__text">
                        <strong><?= e(APP_NAME) ?></strong>
                        <small><?= e($pageKicker) ?></small>
                    </span>
                </a>

                <button class="icon-button icon-button--notify" type="button" aria-label="Probar notificaciones" data-push-test>
                    <?= appIcon('bell') ?>
                    <span class="notify-dot" aria-hidden="true"></span>
                </button>

            </div>
        </header>

        <main class="app-main">
