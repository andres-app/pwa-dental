<?php
$pageTitle = 'Recordatorios';
$activePage = 'mas';
$pageKicker = 'Alertas y seguimiento';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-title-card">
    <h1>Recordatorios</h1>
    <p>Vista preparada para integrar push PWA, WhatsApp y avisos internos de citas.</p>
</section>

<section class="section-head">
    <div>
        <h2>Alertas pendientes</h2>
        <p>Maquetación visual para seguimiento.</p>
    </div>
</section>

<section class="list-stack">
    <article class="simple-card">
        <span class="avatar is-soft"><?= appIcon('bell') ?></span>
        <div class="card-copy">
            <h3>Cita próxima</h3>
            <p>Juan Pérez tiene una cita mañana a las 09:00 AM.</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Push</span></div>
    </article>
    <article class="simple-card">
        <span class="avatar is-soft"><?= appIcon('clock') ?></span>
        <div class="card-copy">
            <h3>Confirmación pendiente</h3>
            <p>Enviar recordatorio a María López para confirmar asistencia.</p>
        </div>
        <div class="card-meta"><span class="status-pill is-warning">WhatsApp</span></div>
    </article>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
