<?php
$pageTitle = 'Agenda';
$activePage = 'agenda';
$pageKicker = 'Agenda diaria';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Agenda de hoy</h1>
    <p>Vista compacta y cómoda para celulares. Puedes reemplazar los datos demo por tus consultas PHP.</p>
</section>

<section class="section-head">
    <div>
        <h2>Miércoles, 01 de julio</h2>
        <p>Citas organizadas por hora.</p>
    </div>
    <a class="btn btn-soft" href="#"><?= appIcon('plus') ?> Agendar</a>
</section>

<section class="timeline-day">
    <article class="appointment-card">
        <span class="time-chip">09:00<small>AM</small></span>
        <div class="card-copy">
            <h3>Juan Carlos Pérez Gómez</h3>
            <p>Control de ortodoncia · Consultorio 1</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Confirmada</span></div>
    </article>
    <article class="appointment-card">
        <span class="time-chip">10:45<small>AM</small></span>
        <div class="card-copy">
            <h3>Lucía Torres</h3>
            <p>Evaluación inicial · Dra. Ana</p>
        </div>
        <div class="card-meta"><span class="status-pill">En espera</span></div>
    </article>
    <article class="appointment-card">
        <span class="time-chip">04:00<small>PM</small></span>
        <div class="card-copy">
            <h3>María López</h3>
            <p>Limpieza dental · Recordatorio enviado</p>
        </div>
        <div class="card-meta"><span class="status-pill is-warning">Pendiente</span></div>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
