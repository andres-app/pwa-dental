<?php
// config/app.php
/*
|--------------------------------------------------------------------------
| Configuración base de la maqueta PWA
|--------------------------------------------------------------------------
| No depende de base de datos. Solo controla rutas, nombre y menú.
| Si tu proyecto está en una subcarpeta, la función appBaseUrl() detecta
| automáticamente la carpeta raíz cuando las páginas están dentro de /pages.
*/

const APP_NAME = 'Dental App';
const APP_VERSION = '8.1';
const APP_THEME_COLOR = '#0ea5b7';
const APP_THEME_DARK = '#073b46';

function appBaseUrl(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');

    // Si el archivo actual está dentro de /pages, volvemos a la raíz del proyecto.
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

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function appNavItems(): array
{
    return [
        'inicio' => [
            'label' => 'Inicio',
            'url' => appUrl('index.php'),
            'icon' => 'home',
        ],
        'pacientes' => [
            'label' => 'Pacientes',
            'url' => appUrl('pages/pacientes.php'),
            'icon' => 'users',
        ],
        'agenda' => [
            'label' => 'Agenda',
            'url' => appUrl('pages/agenda.php'),
            'icon' => 'calendar',
        ],
        'citas' => [
            'label' => 'Citas',
            'url' => appUrl('pages/citas.php'),
            'icon' => 'clipboard',
        ],
        'perfil' => [
            'label' => 'Perfil',
            'url' => appUrl('pages/perfil.php'),
            'icon' => 'users',
        ],
    ];
}
