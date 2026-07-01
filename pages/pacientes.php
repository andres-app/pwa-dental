<?php
$pageTitle = 'Pacientes';
$activePage = 'pacientes';
$pageKicker = 'Gestión de pacientes';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Pacientes</h1>
    <p>Maquetación lista para integrar búsqueda, historial clínico y acciones rápidas por paciente.</p>
</section>

<div class="toolbar">
    <label class="search-box">
        <?= appIcon('search') ?>
        <input type="search" placeholder="Buscar paciente por nombre, DNI o celular" data-demo-search="pacientes">
    </label>
    <a class="btn btn-soft" href="#"><?= appIcon('plus') ?> Nuevo paciente</a>
</div>

<section class="list-stack">
    <article class="patient-card" data-demo-item="pacientes">
        <span class="avatar">JP</span>
        <div class="card-copy">
            <h3>Juan Carlos Pérez Gómez</h3>
            <p>DNI 74581236 · 986 452 120 · Ortodoncia</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Activo</span></div>
    </article>
    <article class="patient-card" data-demo-item="pacientes">
        <span class="avatar">ML</span>
        <div class="card-copy">
            <h3>María Fernanda López</h3>
            <p>DNI 70124563 · 955 741 320 · Limpieza dental</p>
        </div>
        <div class="card-meta"><span class="status-pill">Control</span></div>
    </article>
    <article class="patient-card" data-demo-item="pacientes">
        <span class="avatar">PG</span>
        <div class="card-copy">
            <h3>Pedro Gómez Ríos</h3>
            <p>DNI 76547812 · 974 120 741 · Endodoncia</p>
        </div>
        <div class="card-meta"><span class="status-pill is-warning">Pendiente</span></div>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
