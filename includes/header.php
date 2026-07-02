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
    <link rel="stylesheet" href="<?= e(assetUrl('assets/css/app.css')) ?>">
</head>

<body class="app-body <?= e($bodyClass) ?>" data-page="<?= e($activePage) ?>">
    <div id="appPreloader" class="app-preloader" aria-label="Cargando aplicación">
        <div class="preloader-card">
            <div class="preloader-icon"><?= appIcon('tooth') ?></div>
            <div class="preloader-title">Cargando Dental App</div>
            <div class="preloader-bar"><span></span></div>
        </div>
    </div>

    <div class="app-shell">
        <header class="app-header">
            <div class="app-header__inner">
                <button class="icon-button" type="button" aria-label="Abrir menú">
                    <?= appIcon('menu') ?>
                </button>

                <a class="brand" href="<?= e(appUrl('index.php')) ?>" aria-label="Ir al inicio">
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