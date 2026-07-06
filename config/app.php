<?php
// config/app.php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Configuración base de la PWA
|--------------------------------------------------------------------------
| Controla nombre, versión, rutas, tema visual y navegación principal.
*/

const APP_NAME = 'Dental App';
const APP_VERSION = '9.2';
const APP_SW_VERSION = '93';

const APP_THEME_COLOR = '#0ea5b7';
const APP_THEME_DARK = '#073b46';

/*
|--------------------------------------------------------------------------
| Backend principal
|--------------------------------------------------------------------------
| La PWA consume APIs desde la app web.
*/

const APP_BACKEND_URL = 'https://app.ortodonciaclinica.pe';

function appBaseUrl(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');

    /*
    |--------------------------------------------------------------------------
    | Si el archivo actual está dentro de /pages, /auth, /admin o /api,
    | volvemos a la raíz del proyecto PWA.
    |--------------------------------------------------------------------------
    */

    $dir = preg_replace('#/(pages|admin|auth|api)$#', '', $dir);

    if ($dir === '/' || $dir === '.' || $dir === '') {
        return '';
    }

    return $dir;
}

function appUrl(string $path = ''): string
{
    $base = appBaseUrl();
    $path = ltrim($path, '/');

    if ($path === '') {
        return $base === '' ? '/' : $base . '/';
    }

    return ($base === '' ? '' : $base) . '/' . $path;
}

function assetUrl(string $path): string
{
    return appUrl($path) . '?v=' . rawurlencode(APP_VERSION);
}

function backendUrl(string $path = ''): string
{
    $path = ltrim($path, '/');

    if ($path === '') {
        return APP_BACKEND_URL;
    }

    return rtrim(APP_BACKEND_URL, '/') . '/' . $path;
}

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function appNavItems(): array
{
    return [
        'inicio' => [
            'label' => 'Inicio',
            'url' => appUrl('pages/inicio.php'),
            'icon' => 'home',
            'center' => false,
        ],
        'pacientes' => [
            'label' => 'Pacientes',
            'url' => appUrl('pages/pacientes.php'),
            'icon' => 'users',
            'center' => false,
        ],
        'nueva_cita' => [
            'label' => 'Nueva cita',
            'url' => appUrl('pages/citas.php?accion=nueva'),
            'icon' => 'plus',
            'center' => true,
        ],
        'citas' => [
            'label' => 'Citas',
            'url' => appUrl('pages/citas.php'),
            'icon' => 'calendar',
            'center' => false,
        ],
        'perfil' => [
            'label' => 'Perfil',
            'url' => appUrl('pages/perfil.php'),
            'icon' => 'user',
            'center' => false,
        ],
    ];
}