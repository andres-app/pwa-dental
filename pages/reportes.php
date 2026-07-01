<?php
$pageTitle = 'Reportes';
$activePage = 'mas';
$pageKicker = 'Indicadores';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Reportes</h1>
    <p>Panel base para indicadores de atención, pacientes y productividad.</p>
</section>

<section class="section-head">
    <div>
        <h2>Indicadores principales</h2>
        <p>Resumen visual adaptable.</p>
    </div>
</section>

<section class="stats-grid">
    <article class="stat-card">
        <strong>128</strong>
        <span>Pacientes activos</span>
    </article>
    <article class="stat-card">
        <strong>42</strong>
        <span>Citas del mes</span>
    </article>
    <article class="stat-card">
        <strong>86%</strong>
        <span>Asistencia</span>
    </article>
    <article class="stat-card">
        <strong>12</strong>
        <span>Pendientes</span>
    </article>
</section>

<section class="section-head">
    <div>
        <h2>Bloques futuros</h2>
        <p>Espacios listos para gráficos reales.</p>
    </div>
</section>

<section class="feature-row">
    <article class="feature-box">
        <h3>Atenciones por doctor</h3>
        <p>Contenedor para gráfico de barras o ranking.</p>
    </article>
    <article class="feature-box">
        <h3>Estados de cita</h3>
        <p>Contenedor para gráfico de confirmadas, pendientes y canceladas.</p>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
