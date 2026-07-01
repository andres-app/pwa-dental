<?php
$pageTitle = 'Doctores';
$activePage = 'mas';
$pageKicker = 'Equipo clínico';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Doctores</h1>
    <p>Tarjetas limpias para mostrar doctores, especialidad y disponibilidad.</p>
</section>

<section class="section-head">
    <div>
        <h2>Equipo activo</h2>
        <p>Profesionales disponibles para agenda.</p>
    </div>
    <a class="btn btn-soft" href="#"><?= appIcon('plus') ?> Nuevo</a>
</section>

<section class="list-stack">
    <article class="patient-card">
        <span class="avatar">DA</span>
        <div class="card-copy">
            <h3>Dra. Ana Cárdenas</h3>
            <p>Ortodoncia · Lunes a sábado</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Disponible</span></div>
    </article>
    <article class="patient-card">
        <span class="avatar">DL</span>
        <div class="card-copy">
            <h3>Dr. Luis Ramos</h3>
            <p>Endodoncia · Martes y jueves</p>
        </div>
        <div class="card-meta"><span class="status-pill">Agenda</span></div>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
