<?php
// pages/inicio.php

$pageTitle = 'Inicio';
$activePage = 'inicio';
$pageKicker = 'Panel principal';
$bodyClass = 'home-premium-page';

require_once __DIR__ . '/../includes/header.php';

if (!function_exists('homeIcon')) {
    function homeIcon(string $name): string
    {
        $base = 'width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';

        return match ($name) {
            'bell' => '<svg '.$base.'><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>',
            'tooth' => '<svg '.$base.'><path d="M12 3.5c1.8-1.1 4.7-1.2 6.4.7 2.1 2.4 1.2 6.3-.2 9.7-1.1 2.7-1.3 6.6-3.3 6.6-1.7 0-1.2-4.7-2.9-4.7s-1.2 4.7-2.9 4.7c-2 0-2.2-3.9-3.3-6.6-1.4-3.4-2.3-7.3-.2-9.7 1.7-1.9 4.6-1.8 6.4-.7Z"/></svg>',
            'calendar' => '<svg '.$base.'><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18"/></svg>',
            'check' => '<svg '.$base.'><circle cx="12" cy="12" r="9"/><path d="m8.8 12.2 2.1 2.1 4.5-5"/></svg>',
            'clock' => '<svg '.$base.'><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
            'users' => '<svg '.$base.'><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'arrow' => '<svg '.$base.'><path d="m9 18 6-6-6-6"/></svg>',
            'shield' => '<svg '.$base.'><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-5"/></svg>',
            'doctor' => '<svg '.$base.'><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/><path d="M4 21a8 8 0 0 1 16 0"/><path d="M9 18h6"/><path d="M12 15v6"/></svg>',
            default => '<svg '.$base.'><circle cx="12" cy="12" r="9"/></svg>',
        };
    }
}
?>

<style>
    .app-body.home-premium-page .app-main {
        min-height: 100vh;
        background:
            radial-gradient(circle at 86% 16%, rgba(160, 222, 240, .34), transparent 28%),
            radial-gradient(circle at 8% 0%, rgba(14, 165, 183, .10), transparent 34%),
            linear-gradient(180deg, #f7fcfe 0%, #edf8fb 48%, #f8fbfc 100%);
    }

    .home-premium {
        width: 100%;
        max-width: 430px;
        margin: 0 auto;
        padding-top: 6px;
    }

    .home-hero {
        position: relative;
        min-height: 90px;
        margin-bottom: 8px;
    }

    .home-hello {
        position: relative;
        z-index: 2;
    }

    .home-hello__greeting {
        margin: 0;
        color: #078b9a;
        font-size: 22px;
        line-height: 1;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .home-hello h1 {
        max-width: 250px;
        margin: 4px 0 0;
        overflow: hidden;
        color: #091129;
        font-size: 34px;
        line-height: .96;
        font-weight: 950;
        letter-spacing: -.055em;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .home-hello__date {
        margin: 5px 0 0;
        color: #667085;
        font-size: 14px;
        line-height: 1.15;
        font-weight: 700;
    }

    .home-tooth-art {
        position: absolute;
        top: -2px;
        right: -18px;
        z-index: 1;
        width: 112px;
        height: 112px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        pointer-events: none;
        opacity: .72;
        background:
            radial-gradient(circle at 48% 42%, rgba(255, 255, 255, .90), rgba(255, 255, 255, .64) 32%, rgba(192, 235, 246, .48) 67%, transparent 70%);
        filter: drop-shadow(0 18px 28px rgba(14, 165, 183, .10));
    }

    .home-tooth-art svg {
        width: 56px;
        height: 56px;
        color: #fff;
        stroke-width: 1.3;
        filter: drop-shadow(0 10px 14px rgba(7, 59, 70, .14));
    }

    .summary-card,
    .next-card,
    .home-card,
    .safe-card {
        border: 1px solid rgba(15, 23, 42, .06);
        box-shadow: 0 14px 28px rgba(15, 23, 42, .06);
    }

    .summary-card {
        position: relative;
        z-index: 3;
        border-radius: 24px;
        padding: 16px 16px 18px;
        background: rgba(255, 255, 255, .95);
        box-shadow: 0 18px 44px rgba(15, 23, 42, .08);
    }

    .summary-card__head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, #17c5cc, #087486);
        box-shadow: 0 12px 22px rgba(14, 165, 183, .22);
    }

    .summary-icon svg {
        width: 22px;
        height: 22px;
    }

    .summary-card h2 {
        margin: 0;
        color: #11172f;
        font-size: 16px;
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .summary-kpis {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: stretch;
    }

    .summary-kpi {
        min-height: 96px;
        padding: 0 8px;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        text-align: center;
    }

    .summary-kpi + .summary-kpi {
        border-left: 1px solid rgba(15, 23, 42, .08);
    }

    .summary-kpi__stack {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .summary-kpi__bubble {
        width: 38px;
        height: 38px;
        margin: 0 auto 8px;
        border-radius: 999px;
        display: grid;
        place-items: center;
    }

    .summary-kpi__bubble svg {
        width: 20px;
        height: 20px;
    }

    .summary-kpi__bubble.yellow {
        color: #9a6700;
        background: rgba(251, 191, 36, .18);
    }

    .summary-kpi__bubble.success {
        color: #0c9b72;
        background: rgba(16, 185, 129, .12);
    }

    .summary-kpi__bubble.warning {
        color: #f97316;
        background: rgba(249, 115, 22, .12);
    }

    .summary-kpi__value,
    .summary-kpi__label {
        display: block;
    }

    .summary-kpi__value {
        color: #07102c;
        font-size: 28px;
        line-height: .95;
        font-weight: 950;
        letter-spacing: -.05em;
    }

    .summary-kpi__label {
        margin-top: 8px;
        color: #667085;
        font-size: 12px;
        line-height: 1.2;
        font-weight: 700;
    }

    .next-card {
        margin-top: 18px;
        display: grid;
        grid-template-columns: 98px 1fr 44px;
        align-items: center;
        gap: 10px;
        padding: 14px;
        border-color: rgba(14, 165, 183, .14);
        border-radius: 24px;
        background: rgba(238, 251, 252, .86);
        box-shadow: 0 14px 28px rgba(14, 165, 183, .06);
    }

    .next-time {
        padding-right: 10px;
        border-right: 1px solid rgba(15, 23, 42, .08);
    }

    .next-time strong {
        min-height: 24px;
        padding: 0 8px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        color: #087486;
        background: rgba(14, 165, 183, .12);
        font-size: 10px;
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: .02em;
    }

    .next-time b {
        display: block;
        margin-top: 10px;
        color: #078b9a;
        font-size: 28px;
        line-height: .95;
        font-weight: 950;
        letter-spacing: -.05em;
    }

    .next-time span {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 8px;
        color: #4f99a4;
        font-size: 12px;
        font-weight: 800;
    }

    .next-time span svg {
        width: 17px;
        height: 17px;
    }

    .next-person {
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .next-person__body {
        min-width: 0;
    }

    .next-avatar {
        flex: 0 0 auto;
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        color: #087486;
        background: linear-gradient(135deg, #fff, #e1f9fb);
        box-shadow: inset 0 0 0 1px rgba(14, 165, 183, .12);
    }

    .next-avatar svg {
        width: 22px;
        height: 22px;
    }

    .next-person h3 {
        margin: 0;
        overflow: hidden;
        color: #0c132d;
        font-size: 15px;
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: -.02em;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .next-person p {
        margin: 5px 0 0;
        color: #667085;
        font-size: 12px;
        line-height: 1.2;
        font-weight: 700;
    }

    .next-arrow {
        width: 44px;
        height: 44px;
        border-radius: 16px;
        display: grid;
        place-items: center;
        color: #078b9a;
        background: #fff;
        text-decoration: none;
        box-shadow: 0 10px 18px rgba(15, 23, 42, .06);
    }

    .next-arrow svg {
        width: 21px;
        height: 21px;
    }

    .home-grid {
        margin-top: 18px;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .home-card {
        position: relative;
        min-height: 142px;
        padding: 16px;
        border-radius: 24px;
        overflow: hidden;
        color: #11172f;
        background: rgba(255, 255, 255, .95);
        text-decoration: none;
    }

    .home-card__top {
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .home-card__top > svg {
        width: 20px;
        height: 20px;
        color: #11172f;
    }

    .home-card__icon {
        width: 48px;
        height: 48px;
        border-radius: 18px;
        display: grid;
        place-items: center;
    }

    .home-card__icon svg {
        width: 22px;
        height: 22px;
    }

    .home-card__icon.teal {
        color: #fff;
        background: linear-gradient(135deg, #17c5cc, #087486);
        box-shadow: 0 10px 18px rgba(14, 165, 183, .18);
    }

    .home-card__icon.blue {
        color: #255edf;
        background: rgba(59, 130, 246, .12);
    }

    .home-card__icon.orange {
        color: #f97316;
        background: rgba(249, 115, 22, .12);
    }

    .home-card__icon.indigo {
        color: #3157d6;
        background: rgba(99, 102, 241, .12);
    }

    .home-card h3 {
        margin: 0;
        color: #11172f;
        font-size: 16px;
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .home-card p {
        margin: 6px 0 0;
        color: #078b9a;
        font-size: 12px;
        line-height: 1.2;
        font-weight: 800;
    }

    .safe-card {
        margin-top: 18px;
        padding: 16px;
        border-color: rgba(14, 165, 183, .12);
        border-radius: 24px;
        display: grid;
        grid-template-columns: 54px 1fr;
        align-items: center;
        gap: 12px;
        background: linear-gradient(135deg, rgba(211, 250, 252, .96), rgba(236, 251, 252, .94));
        box-shadow: 0 14px 28px rgba(14, 165, 183, .06);
    }

    .safe-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #fff;
        background: linear-gradient(135deg, #17c5cc, #087486);
        box-shadow: 0 12px 22px rgba(14, 165, 183, .18);
    }

    .safe-icon svg {
        width: 24px;
        height: 24px;
    }

    .safe-card strong {
        display: block;
        color: #087486;
        font-size: 14px;
        line-height: 1.15;
        font-weight: 900;
        letter-spacing: -.02em;
    }

    .safe-card p {
        margin: 4px 0 0;
        color: #317f89;
        font-size: 12px;
        line-height: 1.3;
        font-weight: 700;
    }

    .safe-card a {
        grid-column: 1 / -1;
        min-height: 40px;
        padding: 0 14px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #087486;
        background: #fff;
        font-size: 12px;
        font-weight: 900;
        text-decoration: none;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
    }

    .safe-card a svg {
        width: 18px;
        height: 18px;
    }

    @media (min-width: 521px) {
        .home-premium {
            max-width: 620px;
        }

        .home-hello h1 {
            max-width: 360px;
        }
    }

    @media (max-width: 370px) {
        .app-body.home-premium-page .app-main {
            padding-left: 12px;
            padding-right: 12px;
        }

        .home-hero {
            min-height: 86px;
            margin-bottom: 6px;
        }

        .home-hello__greeting {
            font-size: 20px;
        }

        .home-hello h1 {
            max-width: 220px;
            font-size: 31px;
        }

        .home-hello__date {
            font-size: 13px;
        }

        .home-tooth-art {
            right: -22px;
            width: 104px;
            height: 104px;
        }

        .summary-kpi {
            padding: 0 5px;
        }

        .summary-kpi__value {
            font-size: 25px;
        }

        .summary-kpi__label {
            font-size: 11px;
        }

        .next-card {
            grid-template-columns: 90px 1fr 40px;
            padding: 12px;
        }

        .next-time b {
            font-size: 25px;
        }

        .next-avatar {
            width: 42px;
            height: 42px;
        }

        .next-person h3 {
            font-size: 14px;
        }

        .next-person p {
            font-size: 11px;
        }

        .next-arrow {
            width: 40px;
            height: 40px;
        }

        .home-grid {
            gap: 10px;
        }

        .home-card {
            min-height: 134px;
            padding: 14px;
            border-radius: 22px;
        }

        .home-card__icon {
            width: 44px;
            height: 44px;
        }

        .home-card h3 {
            font-size: 15px;
        }

        .home-card p {
            font-size: 11px;
        }
    }
</style>

<section class="home-premium">
    <section class="home-hero">
        <div class="home-hello">
            <p class="home-hello__greeting" id="homeGreeting">¡Buenos días!</p>
            <h1 id="homeUserName">Usuario 👋</h1>
            <p class="home-hello__date" id="homeDate">Hoy</p>
        </div>

        <div class="home-tooth-art">
            <?= homeIcon('tooth') ?>
        </div>
    </section>

    <section class="summary-card">
        <div class="summary-card__head">
            <div class="summary-icon">
                <?= homeIcon('calendar') ?>
            </div>
            <h2>Resumen de hoy</h2>
        </div>

        <div class="summary-kpis">
            <div class="summary-kpi">
                <div class="summary-kpi__stack">
                    <span class="summary-kpi__bubble yellow"><?= homeIcon('check') ?></span>
                    <span class="summary-kpi__value" id="kpiCitas">0</span>
                    <span class="summary-kpi__label">Programadas</span>
                </div>
            </div>

            <div class="summary-kpi">
                <div class="summary-kpi__stack">
                    <span class="summary-kpi__bubble success"><?= homeIcon('check') ?></span>
                    <span class="summary-kpi__value" id="kpiConfirmadas">0</span>
                    <span class="summary-kpi__label">Confirmadas</span>
                </div>
            </div>

            <div class="summary-kpi">
                <div class="summary-kpi__stack">
                    <span class="summary-kpi__bubble warning"><?= homeIcon('clock') ?></span>
                    <span class="summary-kpi__value" id="kpiPendientes">0</span>
                    <span class="summary-kpi__label">Pendientes</span>
                </div>
            </div>
        </div>
    </section>

    <section class="next-card">
        <div class="next-time">
            <strong>PRÓXIMA CITA</strong>
            <b id="nextHour">--:--</b>
            <span><?= homeIcon('clock') ?> <span id="nextIn">Sin citas</span></span>
        </div>

        <div class="next-person">
            <div class="next-avatar">
                <?= homeIcon('users') ?>
            </div>
            <div class="next-person__body">
                <h3 id="nextPatient">No hay cita próxima</h3>
                <p id="nextDetail">Agenda disponible</p>
            </div>
        </div>

        <a class="next-arrow" href="<?= e(appUrl('pages/citas.php')) ?>" aria-label="Ver citas">
            <?= homeIcon('arrow') ?>
        </a>
    </section>

    <section class="home-grid">
        <a class="home-card" href="<?= e(appUrl('pages/pacientes.php')) ?>">
            <div class="home-card__top">
                <div class="home-card__icon teal"><?= homeIcon('users') ?></div>
                <?= homeIcon('arrow') ?>
            </div>
            <h3>Pacientes</h3>
            <p id="homePacientesText">Ver registrados</p>
        </a>

        <a class="home-card" href="<?= e(appUrl('pages/citas.php')) ?>">
            <div class="home-card__top">
                <div class="home-card__icon blue"><?= homeIcon('calendar') ?></div>
                <?= homeIcon('arrow') ?>
            </div>
            <h3>Citas</h3>
            <p><span id="homeCitasHoy">0</span> citas hoy</p>
        </a>

        <a class="home-card" href="<?= e(appUrl('pages/recordatorios.php')) ?>">
            <div class="home-card__top">
                <div class="home-card__icon orange"><?= homeIcon('bell') ?></div>
                <?= homeIcon('arrow') ?>
            </div>
            <h3>Recordatorios</h3>
            <p><span id="homeRecordatorios">0</span> pendientes</p>
        </a>

        <a class="home-card" href="<?= e(appUrl('pages/doctores.php')) ?>">
            <div class="home-card__top">
                <div class="home-card__icon indigo"><?= homeIcon('doctor') ?></div>
                <?= homeIcon('arrow') ?>
            </div>
            <h3>Doctores</h3>
            <p>Equipo activo</p>
        </a>
    </section>

    <section class="safe-card">
        <div class="safe-icon">
            <?= homeIcon('shield') ?>
        </div>

        <div>
            <strong>Tu información está segura</strong>
            <p>Cumplimos con altos estándares de protección de datos.</p>
        </div>

        <a href="<?= e(appUrl('pages/perfil.php')) ?>">
            Más información <?= homeIcon('arrow') ?>
        </a>
    </section>
</section>

<script>
(function () {
    'use strict';

    const API_ME = 'https://app.ortodonciaclinica.pe/api/auth/me.php';
    const API_CITAS = 'https://app.ortodonciaclinica.pe/api/citas/listar.php';
    const API_NOTIFICACIONES = 'https://app.ortodonciaclinica.pe/api/notificaciones/proximas.php';

    const $ = function (id) {
        return document.getElementById(id);
    };

    function hoyISO() {
        const d = new Date();
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function sumarDiasISO(dias) {
        const d = new Date();
        d.setDate(d.getDate() + dias);
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function normalizarTexto(value) {
        return String(value ?? '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function formatearHora(hora) {
        if (!hora) {
            return '--:--';
        }

        const partes = hora.split(':');
        const h = parseInt(partes[0], 10);
        const m = partes[1] || '00';

        if (Number.isNaN(h)) {
            return hora;
        }

        return `${String(h).padStart(2, '0')}:${m}`;
    }

    function minutosHasta(fecha, hora) {
        const target = new Date(`${fecha}T${hora || '00:00:00'}`);
        const diff = Math.round((target.getTime() - Date.now()) / 60000);

        if (Number.isNaN(diff)) {
            return 'Próximamente';
        }

        if (diff < 0) {
            return 'En curso';
        }

        if (diff === 0) {
            return 'Ahora';
        }

        if (diff < 60) {
            return `En ${diff} min`;
        }

        const horas = Math.floor(diff / 60);
        const mins = diff % 60;

        if (horas < 24) {
            return mins > 0 ? `En ${horas} h ${mins} min` : `En ${horas} h`;
        }

        const dias = Math.floor(horas / 24);

        return dias === 1 ? 'Mañana' : `En ${dias} días`;
    }

    function pintarFecha() {
        const ahora = new Date();
        const hora = ahora.getHours();

        let saludo = '¡Buenos días!';

        if (hora >= 12 && hora < 19) {
            saludo = '¡Buenas tardes!';
        } else if (hora >= 19) {
            saludo = '¡Buenas noches!';
        }

        $('homeGreeting').textContent = saludo;

        const fecha = new Intl.DateTimeFormat('es-PE', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        }).format(ahora);

        $('homeDate').textContent = fecha.charAt(0).toUpperCase() + fecha.slice(1);
    }

    async function fetchJson(url) {
        const response = await fetch(url, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        });

        const texto = await response.text();

        let json = {};

        try {
            json = JSON.parse(texto);
        } catch (e) {
            throw new Error('Respuesta no válida del servidor.');
        }

        if (response.status === 401) {
            window.location.replace('/auth/login.php');
            return null;
        }

        if (!response.ok || json.ok === false) {
            throw new Error(json.mensaje || json.error || 'No se pudo cargar la información.');
        }

        return json;
    }

    async function cargarUsuario() {
        try {
            const json = await fetchJson(API_ME);

            if (!json || !json.usuario) {
                return;
            }

            const nombreCompleto = json.usuario.nombre_usuario || 'Usuario';
            const primerNombre = nombreCompleto.split(' ')[0] || nombreCompleto;
            const nombreCorto = primerNombre.length > 10
                ? primerNombre.substring(0, 10) + '…'
                : primerNombre;

            $('homeUserName').textContent = nombreCorto + ' 👋';

        } catch (error) {
            console.warn(error);
        }
    }

    async function cargarCitas() {
        try {
            const url = new URL(API_CITAS);

            url.searchParams.set('inicio', hoyISO());
            url.searchParams.set('fin', sumarDiasISO(30));

            const json = await fetchJson(url.toString());

            if (!json) {
                return;
            }

            const citas = Array.isArray(json.data) ? json.data : [];
            const hoy = hoyISO();

            const citasHoy = citas.filter(function (cita) {
                return cita.fecha_cita === hoy;
            });

            const confirmadas = citasHoy.filter(function (cita) {
                return normalizarTexto(cita.estado).includes('confirmada');
            });

            const pendientes = citasHoy.filter(function (cita) {
                const estado = normalizarTexto(cita.estado);
                return estado.includes('pendiente') || estado.includes('programada');
            });

            $('kpiCitas').textContent = citasHoy.length;
            $('kpiConfirmadas').textContent = confirmadas.length;
            $('kpiPendientes').textContent = pendientes.length;
            $('homeCitasHoy').textContent = citasHoy.length;

            const ahora = new Date();

            const proximas = citas
                .map(function (cita) {
                    return {
                        ...cita,
                        fechaHora: new Date(`${cita.fecha_cita}T${cita.hora_inicio || '00:00:00'}`)
                    };
                })
                .filter(function (cita) {
                    return cita.fechaHora.getTime() >= ahora.getTime();
                })
                .sort(function (a, b) {
                    return a.fechaHora - b.fechaHora;
                });

            if (proximas.length) {
                const cita = proximas[0];

                $('nextHour').textContent = formatearHora(cita.hora_inicio);
                $('nextIn').textContent = minutosHasta(cita.fecha_cita, cita.hora_inicio);
                $('nextPatient').textContent = cita.paciente || 'Paciente';
                $('nextDetail').textContent = `${cita.servicio || 'Consulta'} · ${cita.motivo || 'Control mensual'}`;
            }

        } catch (error) {
            console.warn(error);
        }
    }

    async function cargarNotificaciones() {
        try {
            const json = await fetchJson(API_NOTIFICACIONES);

            if (!json) {
                return;
            }

            const total = Number(json.total_alertas || 0);

            $('homeRecordatorios').textContent = total;

        } catch (error) {
            console.warn(error);
        }
    }

    pintarFecha();
    cargarUsuario();
    cargarCitas();
    cargarNotificaciones();
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>