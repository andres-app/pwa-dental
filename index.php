<?php
$pageTitle = 'Inicio';
$activePage = 'inicio';
$pageKicker = 'Panel principal';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero-card">
    <div class="hero-card__content">
        <span class="hero-kicker"><?= appIcon('shield') ?> PWA lista para iOS y Android</span>
        <h1>Control dental simple, rápido y elegante.</h1>
        <p>Accede a pacientes, citas, agenda, recordatorios y reportes desde un inicio con botones grandes tipo app móvil.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= e(appUrl('pages/citas.php')) ?>"><?= appIcon('plus') ?> Nueva cita</a>
            <a class="btn btn-secondary" href="<?= e(appUrl('pages/agenda.php')) ?>"><?= appIcon('calendar') ?> Ver agenda</a>
        </div>
    </div>
</section>

<section class="section-head">
    <div>
        <h2>Módulos principales</h2>
        <p>Botones grandes para usar cómodo desde el celular.</p>
    </div>
</section>

<section class="module-grid">
    <a class="module-card" href="<?= e(appUrl('pages/pacientes.php')) ?>">
        <span class="module-badge">128</span>
        <span class="module-icon"><?= appIcon('users') ?></span>
        <h3>Pacientes</h3>
        <p>Ficha, historial clínico y datos de contacto.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/agenda.php')) ?>">
        <span class="module-badge">8</span>
        <span class="module-icon"><?= appIcon('calendar') ?></span>
        <h3>Agenda</h3>
        <p>Vista diaria de citas y próximos turnos.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/citas.php')) ?>">
        <span class="module-badge">12</span>
        <span class="module-icon"><?= appIcon('clipboard') ?></span>
        <h3>Citas</h3>
        <p>Registra, confirma y reorganiza atenciones.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/recordatorios.php')) ?>">
        <span class="module-badge">5</span>
        <span class="module-icon"><?= appIcon('bell') ?></span>
        <h3>Recordatorios</h3>
        <p>Alertas push, WhatsApp y seguimiento.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/historial.php')) ?>">
        <span class="module-icon"><?= appIcon('heart') ?></span>
        <h3>Historial</h3>
        <p>Evolución clínica organizada por paciente.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/doctores.php')) ?>">
        <span class="module-icon"><?= appIcon('tooth') ?></span>
        <h3>Doctores</h3>
        <p>Gestión del equipo profesional y horarios.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/reportes.php')) ?>">
        <span class="module-icon"><?= appIcon('chart') ?></span>
        <h3>Reportes</h3>
        <p>Indicadores rápidos de atención y agenda.</p>
    </a>

    <a class="module-card" href="<?= e(appUrl('pages/mas.php')) ?>">
        <span class="module-icon"><?= appIcon('grid') ?></span>
        <h3>Más opciones</h3>
        <p>Configuración, servicios y administración.</p>
    </a>
</section>

<section class="section-head">
    <div>
        <h2>Resumen de hoy</h2>
        <p>KPIs rápidos para la pantalla inicial.</p>
    </div>
    <a class="section-link" href="<?= e(appUrl('pages/reportes.php')) ?>">Ver todo</a>
</section>

<section class="stats-grid">
    <article class="stat-card">
        <strong>8</strong>
        <span>Citas de hoy</span>
    </article>
    <article class="stat-card">
        <strong>5</strong>
        <span>Confirmadas</span>
    </article>
    <article class="stat-card">
        <strong>3</strong>
        <span>Pendientes</span>
    </article>
    <article class="stat-card">
        <strong>2</strong>
        <span>Doctores activos</span>
    </article>
</section>

<section class="section-head">
    <div>
        <h2>Próximas atenciones</h2>
        <p>Vista compacta desde el home.</p>
    </div>
</section>

<section class="list-stack">
    <article class="appointment-card">
        <span class="time-chip">09:00<small>AM</small></span>
        <div class="card-copy">
            <h3>Juan Pérez Gómez</h3>
            <p>Ortodoncia · Control mensual</p>
        </div>
        <div class="card-meta"><span class="status-pill is-success">Confirmada</span></div>
    </article>
    <article class="appointment-card">
        <span class="time-chip">11:30<small>AM</small></span>
        <div class="card-copy">
            <h3>María López</h3>
            <p>Limpieza dental · Dra. Ana</p>
        </div>
        <div class="card-meta"><span class="status-pill is-warning">Pendiente</span></div>
    </article>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
