<?php
$pageTitle = 'Historial Clínico';
$activePage = 'mas';
$pageKicker = 'Evolución clínica';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Historial clínico</h1>
    <p>Diseño tipo perfil para concentrar la atención en un paciente seleccionado.</p>
</section>

<div class="toolbar">
    <label class="search-box">
        <?= appIcon('search') ?>
        <input type="search" placeholder="Buscar paciente para ver historial">
    </label>
</div>

<section class="panel panel-padding">
    <article class="patient-card">
        <span class="avatar">JP</span>
        <div class="card-copy">
            <h3>Juan Carlos Pérez Gómez</h3>
            <p>Ortodoncia · Última atención: 25/06/2026</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Activo</span></div>
    </article>
</section>

<section class="section-head">
    <div>
        <h2>Evolución</h2>
        <p>Últimos registros clínicos.</p>
    </div>
</section>

<section class="list-stack">
    <article class="simple-card">
        <span class="time-chip">25<small>JUN</small></span>
        <div class="card-copy">
            <h3>Control mensual de ortodoncia</h3>
            <p>Se ajustaron ligas y se recomendó control en 30 días.</p>
        </div>
    </article>
    <article class="simple-card">
        <span class="time-chip">28<small>MAY</small></span>
        <div class="card-copy">
            <h3>Evaluación de avance</h3>
            <p>Paciente sin molestias. Higiene oral en mejora.</p>
        </div>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
