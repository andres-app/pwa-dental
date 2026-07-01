# Dental App PWA - Maquetación Premium PHP

Base visual PWA en PHP, sin base de datos, lista para integrar con tus consultas reales.

## Qué corrige esta versión

- Todas las vistas cargan el mismo CSS y JS mediante `config/app.php`.
- El header usa `viewport-fit=cover` y `env(safe-area-inset-top)` para cubrir el notch/nose del iPhone.
- El footer usa `env(safe-area-inset-bottom)` para respetar la barra inferior de iOS y Android.
- Header y footer son includes reutilizables.
- Inicio rediseñado con módulos grandes tipo app móvil.
- Preloader visual incluido.
- Service Worker y manifest base incluidos.

## Estructura

```txt
pwa_dental_premium_v2/
├── index.php
├── manifest.json
├── service-worker.js
├── config/
│   └── app.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── icons.php
├── assets/
│   ├── css/app.css
│   ├── js/app.js
│   └── img/icons.svg
└── pages/
    ├── pacientes.php
    ├── agenda.php
    ├── citas.php
    ├── recordatorios.php
    ├── historial.php
    ├── doctores.php
    ├── reportes.php
    └── mas.php
```

## Cómo crear una nueva vista

```php
<?php
$pageTitle = 'Servicios';
$activePage = 'mas';
$pageKicker = 'Administración';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title-card">
    <h1>Servicios</h1>
    <p>Contenido de la vista.</p>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
```

## Notas de despliegue

- Para que la PWA funcione como instalada, usa HTTPS.
- El Service Worker funciona en HTTPS o localhost.
- La maqueta no usa base de datos; los datos son demo.
