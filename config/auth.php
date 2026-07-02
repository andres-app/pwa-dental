<?php
// config/auth.php

declare(strict_types=1);

require_once __DIR__ . '/app.php';

const PWA_SESSION_NAME = 'crm_dental_session';
const PWA_BACKEND_ME_URL = 'https://app.ortodonciaclinica.pe/api/auth/me.php';

function pwaSessionCookie(): string
{
    return (string)($_COOKIE[PWA_SESSION_NAME] ?? '');
}

function pwaBackendSesionActiva(): bool
{
    $sessionId = pwaSessionCookie();

    if ($sessionId === '') {
        return false;
    }

    if (!function_exists('curl_init')) {
        return false;
    }

    $ch = curl_init(PWA_BACKEND_ME_URL);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Cookie: ' . PWA_SESSION_NAME . '=' . $sessionId,
        ],
    ]);

    $response = curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($status !== 200 || !$response) {
        return false;
    }

    $json = json_decode($response, true);

    return is_array($json)
        && ($json['ok'] ?? false) === true
        && ($json['logueado'] ?? false) === true;
}

function requiereLoginPwa(): void
{
    if (pwaBackendSesionActiva()) {
        return;
    }

    header('Location: ' . appUrl('auth/login.php'));
    exit;
}