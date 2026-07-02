<?php
$pageTitle = 'Inicio';
$activePage = 'inicio';
$pageKicker = 'Panel principal';
$bodyClass = 'home-page';

$doctorName = $doctorName ?? 'Dra. Ana';

require_once __DIR__ . '/includes/header.php';

$modules = [
    [
        'label' => 'Pacientes',
        'icon' => 'users',
        'url' => appUrl('pages/pacientes.php'),
    ],
    [
        'label' => 'Agenda',
        'icon' => 'calendar',
        'url' => appUrl('pages/agenda.php'),
    ],
    [
        'label' => 'Citas',
        'icon' => 'clipboard',
        'url' => appUrl('pages/citas.php'),
    ],
    [
        'label' => 'Recordatorios',
        'icon' => 'bell',
        'url' => appUrl('pages/recordatorios.php'),
    ],
    [
        'label' => 'Doctores',
        'icon' => 'tooth',
        'url' => appUrl('pages/doctores.php'),
    ],
    [
        'label' => 'Reportes',
        'icon' => 'chart',
        'url' => appUrl('pages/reportes.php'),
    ],
];
?>

<div class="home-center">
    <section class="home-welcome" aria-labelledby="homeWelcomeTitle">
        <span class="home-welcome__logo" aria-hidden="true">
            <?= appIcon('tooth') ?>
        </span>

        <div class="home-welcome__copy">
            <span class="home-welcome__eyebrow">Bienvenido</span>
            <h1 id="homeWelcomeTitle"><?= e($doctorName) ?></h1>
            <p>Selecciona un módulo para continuar.</p>
        </div>
    </section>

    <section class="home-app-grid" aria-label="Módulos principales">
        <?php foreach ($modules as $module): ?>
            <a class="home-module" href="<?= e($module['url']) ?>">
                <span class="home-module__icon" aria-hidden="true">
                    <?= appIcon($module['icon']) ?>
                </span>
                <span class="home-module__label">
                    <?= e($module['label']) ?>
                </span>
            </a>
        <?php endforeach; ?>
    </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>