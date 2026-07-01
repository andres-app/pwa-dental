<?php
$pageTitle = 'Citas';
$activePage = 'citas';
$pageKicker = 'Control de citas';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Citas</h1>
    <p>Diseño de listado tipo app con estados visibles y acciones principales limpias.</p>
</section>

<div class="toolbar">
    <label class="search-box">
        <?= appIcon('search') ?>
        <input type="search" placeholder="Buscar por paciente, doctor o servicio" data-demo-search="citas">
    </label>
    <a class="btn btn-soft" href="#"><?= appIcon('plus') ?> Nueva cita</a>
</div>

<section class="list-stack">
    <article class="appointment-card" data-demo-item="citas">
        <span class="time-chip">09:00<small>AM</small></span>
        <div class="card-copy">
            <h3>Juan Carlos Pérez Gómez</h3>
            <p>Ortodoncia · Dra. Ana · 01/07/2026</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Confirmada</span></div>
    </article>
    <article class="appointment-card" data-demo-item="citas">
        <span class="time-chip">11:30<small>AM</small></span>
        <div class="card-copy">
            <h3>Pedro Gómez Ríos</h3>
            <p>Endodoncia · Dr. Luis · 01/07/2026</p>
        </div>
        <div class="card-meta"><span class="status-pill is-warning">Pendiente</span></div>
    </article>
    <article class="appointment-card" data-demo-item="citas">
        <span class="time-chip">06:00<small>PM</small></span>
        <div class="card-copy">
            <h3>Camila Rojas</h3>
            <p>Resina estética · Dra. Ana · 02/07/2026</p>
        </div>
        <div class="card-meta"><span class="status-pill">Programada</span></div>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
