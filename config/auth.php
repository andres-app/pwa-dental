<?php
// config/auth.php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

const PWA_SESSION_NAME = 'crm_dental_session';

function iniciarSesionPwa(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name(PWA_SESSION_NAME);

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
        || (($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '') === 'on');

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '.ortodonciaclinica.pe',
            'secure' => $https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        session_set_cookie_params(0, '/', '.ortodonciaclinica.pe', $https, true);
    }

    session_start();
}

function pwaSesionActiva(): bool
{
    iniciarSesionPwa();

    return !empty($_SESSION['id_usuario']) && !empty($_SESSION['id_empresa']);
}

function requiereLoginPwa(): void
{
    if (pwaSesionActiva()) {
        return;
    }

    header('Location: ' . appUrl('auth/login.php'));
    exit;
}

function usuarioPwaActual(): array
{
    iniciarSesionPwa();

    return [
        'id_usuario' => (int)($_SESSION['id_usuario'] ?? 0),
        'id_empresa' => (int)($_SESSION['id_empresa'] ?? 0),
        'id_sede' => isset($_SESSION['id_sede']) ? (int)$_SESSION['id_sede'] : null,
        'rol' => (string)($_SESSION['rol'] ?? ''),
        'nombre_usuario' => (string)($_SESSION['nombre_usuario'] ?? ''),
        'empresa_nombre' => (string)($_SESSION['empresa_nombre'] ?? ''),
        'empresa_color' => (string)($_SESSION['empresa_color'] ?? '#00A9B7'),
    ];
}