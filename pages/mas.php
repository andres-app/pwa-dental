<?php
$pageTitle = 'Más';
$activePage = 'mas';
$pageKicker = 'Más módulos';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Más opciones</h1>
    <p>Accesos secundarios sin recargar el footer principal.</p>
</section>

<section class="section-head">
    <div>
        <h2>Módulos adicionales</h2>
        <p>Secciones listas para escalar.</p>
    </div>
</section>

<section class="module-grid">
    <a class="module-card" href="<?= e(appUrl('pages/recordatorios.php')) ?>">
        <span class="module-icon"><?= appIcon('bell') ?></span>
        <h3>Recordatorios</h3>
        <p>Push, WhatsApp y avisos.</p>
    </a>
    <a class="module-card" href="<?= e(appUrl('pages/historial.php')) ?>">
        <span class="module-icon"><?= appIcon('heart') ?></span>
        <h3>Historial</h3>
        <p>Evolución clínica.</p>
    </a>
    <a class="module-card" href="<?= e(appUrl('pages/doctores.php')) ?>">
        <span class="module-icon"><?= appIcon('tooth') ?></span>
        <h3>Doctores</h3>
        <p>Equipo profesional.</p>
    </a>
    <a class="module-card" href="<?= e(appUrl('pages/reportes.php')) ?>">
        <span class="module-icon"><?= appIcon('chart') ?></span>
        <h3>Reportes</h3>
        <p>Indicadores y KPIs.</p>
    </a>
</section>

<section class="section-head">
    <div>
        <h2>Estado de maqueta</h2>
        <p>Base visual limpia para integrar backend.</p>
    </div>
</section>

<article class="notice-card">
    <strong>Rutas corregidas</strong>
    <p>Todas las páginas usan <code>appUrl()</code> y <code>assetUrl()</code>, por eso el CSS ya no se rompe al entrar a vistas dentro de la carpeta <code>/pages</code>.</p>
</article>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
