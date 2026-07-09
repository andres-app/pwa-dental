<?php
$pageTitle = 'Citas';
$activePage = 'citas';
$pageKicker = 'Citas de pacientes';

$tz = new DateTimeZone('America/Lima');
$hoyObj = new DateTimeImmutable('now', $tz);
$hoy = $hoyObj->format('Y-m-d');

require_once __DIR__ . '/../includes/header.php';

if (!function_exists('miniIcon')) {
    function miniIcon(string $name): string
    {
        $icons = [
            'calendar' => '<svg viewBox="0 0 24 24" fill="none"><path d="M7 3v3M17 3v3M4 9h16M6 5h12a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
            'clock' => '<svg viewBox="0 0 24 24" fill="none"><path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
            'refresh' => '<svg viewBox="0 0 24 24" fill="none"><path d="M20 12a8 8 0 1 1-2.35-5.65M20 4v5h-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'left' => '<svg viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'right' => '<svg viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'search' => '<svg viewBox="0 0 24 24" fill="none"><path d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
}
?>

<style>
    .weekly-calendar-page {
        padding-bottom: calc(96px + env(safe-area-inset-bottom));
    }

    .week-toolbar {
        display: grid;
        grid-template-columns: 48px 1fr 48px;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .week-nav-btn {
        width: 48px;
        height: 48px;
        border: 0;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #0f172a;
        background: rgba(255, 255, 255, .90);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        border: 1px solid rgba(15, 23, 42, .06);
    }

    .week-nav-btn svg,
    .summary-icon svg,
    .empty-state svg,
    .calendar-search svg {
        width: 22px;
        height: 22px;
    }

    .week-range-card {
        min-height: 48px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #0f172a;
        background: rgba(255, 255, 255, .90);
        box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        border: 1px solid rgba(15, 23, 42, .06);
        font-size: .88rem;
        font-weight: 950;
        text-align: center;
    }

    .week-range-card span {
        color: #0ea5b7;
        display: grid;
        place-items: center;
    }

    .calendar-search {
        min-height: 54px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 16px;
        margin-bottom: 14px;
        background: rgba(255, 255, 255, .92);
        border: 1px solid rgba(15, 23, 42, .07);
        box-shadow: 0 16px 36px rgba(15, 23, 42, .06);
        color: #98a2b3;
    }

    .calendar-search input {
        width: 100%;
        min-width: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: #101828;
        font-size: 15px;
        font-weight: 750;
    }

    .calendar-search input::placeholder {
        color: #98a2b3;
    }

    .calendar-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin: 16px 0 18px;
    }

    .summary-card {
        min-height: 106px;
        border-radius: 24px;
        padding: 14px 12px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .06);
        box-shadow: 0 16px 38px rgba(15, 23, 42, .07);
    }

    .summary-icon {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        margin-bottom: 10px;
    }

    .summary-card strong {
        display: block;
        font-size: 1.7rem;
        line-height: 1;
        font-weight: 950;
        letter-spacing: -.04em;
    }

    .summary-card small {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: .72rem;
        font-weight: 750;
        line-height: 1.1;
    }

    .summary-card.is-today,
    .summary-card.is-today .summary-icon {
        color: #0ea5b7;
    }

    .summary-card.is-today .summary-icon {
        background: rgba(14, 165, 183, .11);
    }

    .summary-card.is-warning,
    .summary-card.is-warning .summary-icon {
        color: #d97706;
    }

    .summary-card.is-warning .summary-icon {
        background: rgba(245, 158, 11, .12);
    }

    .summary-card.is-info,
    .summary-card.is-info .summary-icon {
        color: #2563eb;
    }

    .summary-card.is-info .summary-icon {
        background: rgba(37, 99, 235, .11);
    }

    .day-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 4px 2px 12px;
        margin-bottom: 8px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .day-tabs::-webkit-scrollbar {
        display: none;
    }

    .day-tab {
        flex: 0 0 auto;
        min-width: 74px;
        border: 0;
        border-radius: 18px;
        padding: 10px 12px;
        background: #fff;
        color: #64748b;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        border: 1px solid rgba(15, 23, 42, .06);
        text-align: center;
    }

    .day-tab strong {
        display: block;
        font-size: .82rem;
        font-weight: 900;
    }

    .day-tab span {
        display: block;
        margin-top: 4px;
        font-size: .72rem;
        font-weight: 800;
    }

    .day-tab em {
        display: inline-flex;
        margin-top: 6px;
        min-width: 22px;
        height: 22px;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        font-size: .66rem;
        font-style: normal;
        font-weight: 950;
        background: rgba(15, 23, 42, .06);
    }

    .day-tab.is-active {
        color: #fff;
        background: linear-gradient(135deg, #0ea5b7, #14b8a6);
        box-shadow: 0 16px 32px rgba(14, 165, 183, .28);
    }

    .day-tab.is-active em {
        background: rgba(255, 255, 255, .22);
    }

    .day-carousel-shell {
        position: relative;
        overflow: hidden;
        border-radius: 30px;
        background:
            radial-gradient(circle at 10% 0%, rgba(14, 165, 183, .12), transparent 32%),
            linear-gradient(180deg, #ffffff 0%, #f8fbfc 100%);
        border: 1px solid rgba(15, 23, 42, .07);
        box-shadow: 0 20px 50px rgba(15, 23, 42, .08);
    }

    .day-carousel-track {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .day-carousel-track::-webkit-scrollbar {
        display: none;
    }

    .day-slide {
        flex: 0 0 100%;
        width: 100%;
        scroll-snap-align: start;
        padding: 18px;
        min-height: 560px;
    }

    .day-slide-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(15, 23, 42, .07);
        margin-bottom: 16px;
    }

    .day-slide-title small {
        display: block;
        color: #0ea5b7;
        font-weight: 900;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 5px;
    }

    .day-slide-title h3 {
        margin: 0;
        color: #0f172a;
        font-size: 1.55rem;
        line-height: 1;
        font-weight: 950;
        letter-spacing: -.05em;
    }

    .day-counter {
        min-width: 76px;
        padding: 10px 12px;
        border-radius: 18px;
        text-align: center;
        background: rgba(14, 165, 183, .10);
        color: #0ea5b7;
        font-weight: 900;
    }

    .day-counter strong {
        display: block;
        font-size: 1.45rem;
        line-height: 1;
    }

    .day-counter span {
        display: block;
        margin-top: 4px;
        font-size: .68rem;
    }

    .appointments-list {
        display: grid;
        gap: 12px;
    }

    .appointment-card-day {
        position: relative;
        display: grid;
        grid-template-columns: 76px 1fr;
        gap: 12px;
        padding: 14px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .06);
        box-shadow: 0 14px 32px rgba(15, 23, 42, .07);
        overflow: hidden;
        color: var(--status-color, #0ea5b7);
    }

    .appointment-card-day::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 5px;
        background: currentColor;
    }

    .appointment-card-day.is-success {
        background: linear-gradient(135deg, rgba(14, 165, 183, .10), rgba(255, 255, 255, .98));
    }

    .appointment-card-day.is-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, .12), rgba(255, 255, 255, .98));
    }

    .appointment-card-day.is-info {
        background: linear-gradient(135deg, rgba(37, 99, 235, .10), rgba(255, 255, 255, .98));
    }

    .appointment-card-day.is-danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, .10), rgba(255, 255, 255, .98));
    }

    .appointment-card-day.is-muted {
        background: linear-gradient(135deg, rgba(100, 116, 139, .10), rgba(255, 255, 255, .98));
    }

    .appointment-hour {
        display: grid;
        place-items: center;
        min-height: 76px;
        border-radius: 20px;
        background: rgba(255, 255, 255, .72);
        color: currentColor;
        font-size: 1.08rem;
        font-weight: 950;
        letter-spacing: -.03em;
    }

    .appointment-info {
        min-width: 0;
    }

    .appointment-info h4 {
        margin: 0;
        color: #0f172a;
        font-size: 1.05rem;
        line-height: 1.12;
        font-weight: 950;
        letter-spacing: -.03em;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .appointment-info p {
        margin: 5px 0 0;
        color: #475569;
        font-size: .86rem;
        line-height: 1.25;
        font-weight: 750;
    }

    .appointment-info .doctor {
        color: #64748b;
        font-size: .78rem;
        font-weight: 700;
    }

    .appointment-status-day {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        color: currentColor;
        font-size: .72rem;
        font-weight: 950;
        padding: 7px 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .76);
    }

    .appointment-status-day::before {
        content: "";
        width: 8px;
        height: 8px;
        flex: 0 0 8px;
        border-radius: 999px;
        background: currentColor;
    }

    .empty-state {
        min-height: 360px;
        display: grid;
        place-items: center;
        text-align: center;
        color: #94a3b8;
        padding: 30px 10px;
    }

    .empty-state div {
        display: grid;
        justify-items: center;
        gap: 10px;
    }

    .empty-state h4 {
        margin: 0;
        color: #475569;
        font-size: 1.1rem;
        font-weight: 900;
    }

    .empty-state p {
        margin: 0;
        max-width: 240px;
        color: #94a3b8;
        font-size: .86rem;
        line-height: 1.35;
        font-weight: 700;
    }

    .day-nav {
        display: grid;
        grid-template-columns: 48px 1fr 48px;
        gap: 10px;
        align-items: center;
        padding: 14px;
        border-top: 1px solid rgba(15, 23, 42, .07);
        background: rgba(255, 255, 255, .78);
        backdrop-filter: blur(16px);
    }

    .day-nav-btn {
        width: 48px;
        height: 48px;
        border: 0;
        border-radius: 18px;
        display: grid;
        place-items: center;
        color: #0f172a;
        background: #f8fafc;
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, .06);
    }

    .day-nav-label {
        text-align: center;
        color: #64748b;
        font-size: .8rem;
        font-weight: 850;
    }

    .calendar-loader {
        min-height: 280px;
        display: grid;
        place-items: center;
        color: #64748b;
        font-weight: 850;
        text-align: center;
        padding: 30px;
    }

    .calendar-loader span {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        border: 3px solid rgba(14, 165, 183, .18);
        border-top-color: #0ea5b7;
        animation: spin .8s linear infinite;
        display: inline-block;
        margin-bottom: 12px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .ios-sheet-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: flex-end;
        background: rgba(15, 23, 42, .28);
        backdrop-filter: blur(8px);
        padding: 12px;
        padding-bottom: calc(12px + env(safe-area-inset-bottom));
    }

    .ios-sheet-backdrop[hidden] {
        display: none;
    }

    .ios-sheet {
        width: 100%;
        max-height: min(78vh, 640px);
        overflow-y: auto;
        border-radius: 30px;
        border: 1px solid rgba(255, 255, 255, .74);
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 -24px 60px rgba(15, 23, 42, .22);
        padding: 10px 18px 18px;
    }

    .ios-sheet-handle {
        display: block;
        width: 46px;
        height: 5px;
        border: 0;
        border-radius: 999px;
        background: #d0d5dd;
        margin: 2px auto 16px;
    }

    .sheet-title {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
        margin-bottom: 14px;
    }

    .sheet-title h2 {
        margin: 0;
        color: #101828;
        font-size: 22px;
        letter-spacing: -.04em;
        line-height: 1.05;
    }

    .sheet-title p {
        margin: 6px 0 0;
        color: #667085;
        font-size: 13px;
        font-weight: 750;
    }

    .sheet-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .sheet-item,
    .sheet-notes {
        border-radius: 18px;
        border: 1px solid rgba(15, 23, 42, .06);
        background: #f8fafc;
        padding: 12px;
    }

    .sheet-notes {
        margin-top: 10px;
    }

    .sheet-item span,
    .sheet-notes span {
        display: block;
        color: #98a2b3;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .sheet-item strong,
    .sheet-notes p {
        display: block;
        margin: 5px 0 0;
        color: #344054;
        font-size: 13px;
        line-height: 1.35;
        font-weight: 750;
    }

    .sheet-actions {
        display: flex;
        gap: 10px;
        margin-top: 14px;
    }

    .sheet-actions a,
    .sheet-actions button {
        flex: 1;
        min-height: 46px;
        border: 0;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 950;
    }

    .sheet-actions a {
        color: #047857;
        background: rgba(16, 185, 129, .12);
    }

    .sheet-actions button {
        color: #fff;
        background: linear-gradient(135deg, #0ea5b7, #38bdf8);
    }

    .sheet-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 30px;
        padding: 0 10px;
        border-radius: 999px;
        color: var(--status-color, #64748b);
        background: color-mix(in srgb, var(--status-color, #64748b) 12%, white);
        font-size: 11px;
        font-weight: 950;
        white-space: nowrap;
    }

    .sheet-status::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: currentColor;
    }

    @media (max-width: 480px) {
        .calendar-summary {
            gap: 8px;
        }

        .summary-card {
            min-height: 102px;
            padding: 12px 10px;
            border-radius: 22px;
        }

        .summary-card strong {
            font-size: 1.48rem;
        }

        .summary-card small {
            font-size: .66rem;
        }

        .day-slide {
            padding: 16px;
            min-height: 540px;
        }

        .appointment-card-day {
            grid-template-columns: 68px 1fr;
            padding: 12px;
            border-radius: 22px;
        }

        .appointment-hour {
            min-height: 70px;
            border-radius: 18px;
            font-size: .98rem;
        }

        .appointment-info h4 {
            font-size: .98rem;
        }

        .appointment-info p {
            font-size: .8rem;
        }

        .sheet-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="weekly-calendar-page" data-today="<?= e($hoy) ?>">

    <div class="week-toolbar">
        <button type="button" class="week-nav-btn" id="prevWeek" aria-label="Semana anterior">
            <?= miniIcon('left') ?>
        </button>

        <div class="week-range-card">
            <span><?= miniIcon('calendar') ?></span>
            <strong id="weekRangeText">Cargando semana...</strong>
        </div>

        <button type="button" class="week-nav-btn" id="nextWeek" aria-label="Semana siguiente">
            <?= miniIcon('right') ?>
        </button>
    </div>

    <label class="calendar-search">
        <?= miniIcon('search') ?>
        <input
            type="search"
            id="buscarCitaSemana"
            placeholder="Buscar paciente, doctor o servicio"
            autocomplete="off"
            inputmode="search">
    </label>

    <section class="calendar-summary">
        <article class="summary-card is-today">
            <div class="summary-icon"><?= miniIcon('calendar') ?></div>
            <strong id="metricCitasDia">0</strong>
            <small>citas del día</small>
        </article>

        <article class="summary-card is-warning">
            <div class="summary-icon"><?= miniIcon('clock') ?></div>
            <strong id="metricPendientes">0</strong>
            <small>pendientes</small>
        </article>

        <article class="summary-card is-info">
            <div class="summary-icon"><?= miniIcon('refresh') ?></div>
            <strong id="metricSemana">0</strong>
            <small>citas semana</small>
        </article>
    </section>

    <section class="section-head">
        <div>
            <h2>Agenda semanal</h2>
            <p>Desliza hacia la derecha para ver el siguiente día.</p>
        </div>
    </section>

    <section class="day-tabs" id="dayTabs"></section>

    <section class="day-carousel-shell">
        <div class="day-carousel-track" id="dayCarousel">
            <div class="calendar-loader">
                <div>
                    <span></span>
                    <p>Cargando citas de la semana...</p>
                </div>
            </div>
        </div>

        <div class="day-nav">
            <button type="button" class="day-nav-btn" id="prevDay" aria-label="Día anterior">
                <?= miniIcon('left') ?>
            </button>

            <div class="day-nav-label" id="dayNavLabel">
                Cargando...
            </div>

            <button type="button" class="day-nav-btn" id="nextDay" aria-label="Día siguiente">
                <?= miniIcon('right') ?>
            </button>
        </div>
    </section>

</main>

<div class="ios-sheet-backdrop" id="detalleSheet" hidden>
    <section class="ios-sheet" role="dialog" aria-modal="true" aria-labelledby="detalleTitulo">
        <button type="button" class="ios-sheet-handle" data-close-sheet aria-label="Cerrar detalle"></button>
        <div id="detalleContenido"></div>
    </section>
</div>

<script>
(function () {
    'use strict';

    const API_URL = 'https://app.ortodonciaclinica.pe/api/citas/listar.php';
    const PER_PAGE = 50;

    const root = document.querySelector('.weekly-calendar-page');
    const todayISO = root?.dataset.today || fechaLocalActual();

    const dayTabs = document.getElementById('dayTabs');
    const carousel = document.getElementById('dayCarousel');
    const prevDay = document.getElementById('prevDay');
    const nextDay = document.getElementById('nextDay');
    const prevWeek = document.getElementById('prevWeek');
    const nextWeek = document.getElementById('nextWeek');
    const label = document.getElementById('dayNavLabel');
    const weekRangeText = document.getElementById('weekRangeText');
    const buscarInput = document.getElementById('buscarCitaSemana');

    const metricCitasDia = document.getElementById('metricCitasDia');
    const metricPendientes = document.getElementById('metricPendientes');
    const metricSemana = document.getElementById('metricSemana');

    const sheet = document.getElementById('detalleSheet');
    const detalleContenido = document.getElementById('detalleContenido');

    let activeIndex = 0;
    let weekStart = lunesDeSemana(todayISO);
    let weekDays = [];
    let citasSemana = [];
    let debounceBuscar = null;
    let controller = null;

    function fechaLocalActual() {
        const ahora = new Date();
        const offset = ahora.getTimezoneOffset();
        const local = new Date(ahora.getTime() - (offset * 60000));
        return local.toISOString().slice(0, 10);
    }

    function dateFromISO(iso) {
        const [y, m, d] = String(iso).split('-').map(Number);
        return new Date(y, m - 1, d);
    }

    function isoFromDate(date) {
        const offset = date.getTimezoneOffset();
        const local = new Date(date.getTime() - (offset * 60000));
        return local.toISOString().slice(0, 10);
    }

    function sumarDias(iso, dias) {
        const d = dateFromISO(iso);
        d.setDate(d.getDate() + dias);
        return isoFromDate(d);
    }

    function lunesDeSemana(iso) {
        const d = dateFromISO(iso);
        const day = d.getDay();
        const diff = day === 0 ? -6 : 1 - day;
        d.setDate(d.getDate() + diff);
        return isoFromDate(d);
    }

    function nombreDia(iso, largo = true) {
        const d = dateFromISO(iso);
        return d.toLocaleDateString('es-PE', {
            weekday: largo ? 'long' : 'short'
        }).replace('.', '');
    }

    function fechaCorta(iso) {
        const d = dateFromISO(iso);
        return d.toLocaleDateString('es-PE', {
            day: 'numeric',
            month: 'short'
        }).replace('.', '');
    }

    function fechaLarga(iso) {
        const d = dateFromISO(iso);
        return d.toLocaleDateString('es-PE', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });
    }

    function formatearFecha(iso) {
        if (!iso) return '';
        const [y, m, d] = iso.split('-');
        return `${d}/${m}/${y}`;
    }

    function formatearHora(hora) {
        if (!hora) return { hora: '--:--', periodo: '' };

        const partes = String(hora).split(':');
        let h = parseInt(partes[0], 10);
        const m = partes[1] || '00';

        if (Number.isNaN(h)) {
            return { hora: hora, periodo: '' };
        }

        const periodo = h >= 12 ? 'PM' : 'AM';
        let h12 = h % 12;

        if (h12 === 0) h12 = 12;

        return {
            hora: `${String(h12).padStart(2, '0')}:${m}`,
            periodo
        };
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function normalizarTexto(value) {
        return String(value ?? '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function colorSeguro(color, estadoNombre) {
        const value = String(color ?? '').trim();

        if (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(value)) {
            return value;
        }

        const n = normalizarTexto(estadoNombre);

        if (n.includes('pendiente')) return '#F59E0B';
        if (n.includes('confirmada')) return '#10B981';
        if (n.includes('atendida')) return '#3B82F6';
        if (n.includes('cancelada')) return '#EF4444';
        if (n.includes('no asistio')) return '#6B7280';

        return '#64748B';
    }

    function claseEstado(estadoNombre) {
        const n = normalizarTexto(estadoNombre);

        if (n.includes('pendiente')) return 'is-warning';
        if (n.includes('confirmada')) return 'is-success';
        if (n.includes('atendida')) return 'is-info';
        if (n.includes('cancelada')) return 'is-danger';
        if (n.includes('no asistio')) return 'is-muted';

        return 'is-muted';
    }

    function whatsappUrl(numero, mensaje) {
        const limpio = String(numero ?? '').replace(/\D/g, '');
        if (!limpio) return '';

        const numeroFinal = limpio.startsWith('51') ? limpio : `51${limpio}`;
        return `https://wa.me/${numeroFinal}?text=${encodeURIComponent(mensaje)}`;
    }

    function crearSemana() {
        weekDays = Array.from({ length: 7 }, (_, index) => {
            const iso = sumarDias(weekStart, index);

            return {
                iso,
                name: nombreDia(iso, true),
                short: nombreDia(iso, false),
                date: fechaCorta(iso),
                items: []
            };
        });

        weekRangeText.textContent = `${fechaCorta(weekDays[0].iso)} - ${fechaCorta(weekDays[6].iso)}`;
    }

    function agruparCitas() {
        const porDia = new Map();

        weekDays.forEach(day => {
            porDia.set(day.iso, []);
        });

        citasSemana.forEach(cita => {
            const fecha = cita.fecha_cita;
            if (!porDia.has(fecha)) return;
            porDia.get(fecha).push(cita);
        });

        weekDays.forEach(day => {
            day.items = porDia.get(day.iso) || [];
            day.items.sort((a, b) => String(a.hora_inicio || '').localeCompare(String(b.hora_inicio || '')));
        });
    }

    function pintarTabs() {
        dayTabs.innerHTML = weekDays.map((day, index) => `
            <button
                type="button"
                class="day-tab ${index === activeIndex ? 'is-active' : ''}"
                data-day-tab="${index}">
                <strong>${escapeHtml(capitalizar(day.short))}</strong>
                <span>${escapeHtml(day.date)}</span>
                <em>${day.items.length}</em>
            </button>
        `).join('');

        dayTabs.querySelectorAll('[data-day-tab]').forEach(btn => {
            btn.addEventListener('click', () => goToDay(Number(btn.dataset.dayTab || 0)));
        });
    }

    function pintarCarrusel() {
        carousel.innerHTML = weekDays.map((day, index) => `
            <article class="day-slide" data-day-slide="${index}">
                <div class="day-slide-head">
                    <div class="day-slide-title">
                        <small>${escapeHtml(day.date)}</small>
                        <h3>${escapeHtml(capitalizar(day.name))}</h3>
                    </div>

                    <div class="day-counter">
                        <strong>${day.items.length}</strong>
                        <span>citas</span>
                    </div>
                </div>

                ${day.items.length ? `
                    <div class="appointments-list">
                        ${day.items.map(tarjetaCita).join('')}
                    </div>
                ` : `
                    <div class="empty-state">
                        <div>
                            <?= miniIcon('calendar') ?>
                            <h4>Sin citas</h4>
                            <p>No hay citas programadas para este día.</p>
                        </div>
                    </div>
                `}
            </article>
        `).join('');

        carousel.querySelectorAll('[data-cita]').forEach(card => {
            card.addEventListener('click', () => {
                try {
                    abrirDetalle(JSON.parse(card.dataset.cita || '{}'));
                } catch (error) {}
            });
        });
    }

    function tarjetaCita(cita) {
        const hora = formatearHora(cita.hora_inicio);
        const estadoNombre = cita.estado || 'Sin estado';
        const estadoColor = colorSeguro(cita.color, estadoNombre);
        const paciente = cita.paciente || 'Paciente sin nombre';
        const servicio = cita.servicio || 'Sin servicio';
        const doctor = cita.doctor || 'Sin doctor';
        const clase = claseEstado(estadoNombre);

        return `
            <article
                class="appointment-card-day ${clase}"
                style="--status-color:${escapeHtml(estadoColor)}"
                data-cita="${escapeHtml(JSON.stringify(cita))}">
                <div class="appointment-hour">
                    <div>
                        <strong>${escapeHtml(hora.hora)}</strong>
                        <small>${escapeHtml(hora.periodo)}</small>
                    </div>
                </div>

                <div class="appointment-info">
                    <h4>${escapeHtml(paciente)}</h4>
                    <p>${escapeHtml(servicio)}</p>
                    <p class="doctor">${escapeHtml(doctor)}</p>
                    <span class="appointment-status-day">
                        ${escapeHtml(estadoNombre)}
                    </span>
                </div>
            </article>
        `;
    }

    function pintarResumen() {
        const diaActual = weekDays[activeIndex] || weekDays[0];
        const citasDia = diaActual ? diaActual.items.length : 0;

        const pendientes = citasSemana.filter(cita => {
            return normalizarTexto(cita.estado).includes('pendiente');
        }).length;

        metricCitasDia.textContent = citasDia;
        metricPendientes.textContent = pendientes;
        metricSemana.textContent = citasSemana.length;
    }

    function goToDay(index, smooth = true) {
        const slides = Array.from(document.querySelectorAll('[data-day-slide]'));

        if (!slides.length) return;

        if (index < 0) index = 0;
        if (index > slides.length - 1) index = slides.length - 1;

        activeIndex = index;

        carousel.scrollTo({
            left: slides[index].offsetLeft,
            behavior: smooth ? 'smooth' : 'auto'
        });

        dayTabs.querySelectorAll('[data-day-tab]').forEach(tab => {
            tab.classList.remove('is-active');
        });

        const tab = dayTabs.querySelector(`[data-day-tab="${index}"]`);

        if (tab) {
            tab.classList.add('is-active');
            tab.scrollIntoView({
                behavior: smooth ? 'smooth' : 'auto',
                inline: 'center',
                block: 'nearest'
            });
        }

        const day = weekDays[index];

        label.textContent = day ? `${capitalizar(day.name)}, ${day.date}` : '';

        prevDay.style.opacity = index === 0 ? '.45' : '1';
        nextDay.style.opacity = index === weekDays.length - 1 ? '.45' : '1';

        pintarResumen();
    }

    async function cargarSemana() {
        crearSemana();

        carousel.innerHTML = `
            <div class="calendar-loader">
                <div>
                    <span></span>
                    <p>Cargando citas de la semana...</p>
                </div>
            </div>
        `;

        if (controller) controller.abort();
        controller = new AbortController();

        citasSemana = [];

        try {
            let pagina = 1;
            let seguir = true;

            while (seguir) {
                const url = new URL(API_URL, window.location.href);

                url.searchParams.set('inicio', weekDays[0].iso);
                url.searchParams.set('fin', weekDays[6].iso);
                url.searchParams.set('alcance', 'rango');
                url.searchParams.set('estado', '0');
                url.searchParams.set('buscar', buscarInput.value.trim());
                url.searchParams.set('pagina', String(pagina));
                url.searchParams.set('por_pagina', String(PER_PAGE));

                const response = await fetch(url.toString(), {
                    method: 'GET',
                    credentials: 'include',
                    headers: { 'Accept': 'application/json' },
                    signal: controller.signal
                });

                const text = await response.text();
                let json;

                try {
                    json = JSON.parse(text);
                } catch (error) {
                    console.error('Respuesta no JSON:', text);
                    throw new Error('El API no devolvió JSON.');
                }

                if (!response.ok || !json.ok) {
                    console.error('Error del API:', json);
                    throw new Error(json.mensaje || json.error || 'No se pudieron cargar las citas.');
                }

                const data = Array.isArray(json.data) ? json.data : [];
                citasSemana = citasSemana.concat(data);

                const pag = json.paginacion || {};
                const totalPaginas = Number(pag.total_paginas || 1);

                seguir = data.length === PER_PAGE && pagina < totalPaginas;
                pagina++;
            }

            agruparCitas();
            pintarTabs();
            pintarCarrusel();

            const indexHoy = weekDays.findIndex(day => day.iso === todayISO);
            activeIndex = indexHoy >= 0 ? indexHoy : 0;

            requestAnimationFrame(() => goToDay(activeIndex, false));

        } catch (error) {
            if (error.name === 'AbortError') return;

            console.error(error);

            carousel.innerHTML = `
                <div class="empty-state">
                    <div>
                        <?= miniIcon('calendar') ?>
                        <h4>No se pudieron cargar las citas</h4>
                        <p>Verifica la sesión, conexión o el API de citas.</p>
                    </div>
                </div>
            `;

            metricCitasDia.textContent = '0';
            metricPendientes.textContent = '0';
            metricSemana.textContent = '0';
            label.textContent = 'Sin datos';
        }
    }

    function abrirDetalle(cita) {
        const horaInicio = formatearHora(cita.hora_inicio);
        const horaFin = formatearHora(cita.hora_fin);
        const estadoNombre = cita.estado || 'Sin estado';
        const estadoColor = colorSeguro(cita.color, estadoNombre);
        const paciente = cita.paciente || 'Paciente sin nombre';
        const servicio = cita.servicio || 'Sin servicio';
        const doctor = cita.doctor || 'Sin doctor';
        const fecha = formatearFecha(cita.fecha_cita);
        const mensaje = `Hola ${paciente}, te recordamos tu cita dental para el ${fecha} a las ${horaInicio.hora} ${horaInicio.periodo}. Te esperamos.`;
        const wa = whatsappUrl(cita.whatsapp, mensaje);

        detalleContenido.innerHTML = `
            <div class="sheet-title">
                <div>
                    <h2 id="detalleTitulo">${escapeHtml(paciente)}</h2>
                    <p>${escapeHtml(servicio)}</p>
                </div>

                <span class="sheet-status" style="--status-color:${escapeHtml(estadoColor)}">
                    ${escapeHtml(estadoNombre)}
                </span>
            </div>

            <div class="sheet-grid">
                <div class="sheet-item"><span>Fecha</span><strong>${escapeHtml(fecha)}</strong></div>
                <div class="sheet-item"><span>Hora</span><strong>${escapeHtml(horaInicio.hora)} ${escapeHtml(horaInicio.periodo)} - ${escapeHtml(horaFin.hora)} ${escapeHtml(horaFin.periodo)}</strong></div>
                <div class="sheet-item"><span>Doctor</span><strong>${escapeHtml(doctor)}</strong></div>
                <div class="sheet-item"><span>Canal</span><strong>${escapeHtml(cita.canal_registro || 'No indicado')}</strong></div>
                <div class="sheet-item"><span>Documento</span><strong>${escapeHtml(cita.numero_documento || 'No registrado')}</strong></div>
                <div class="sheet-item"><span>WhatsApp</span><strong>${escapeHtml(cita.whatsapp || 'No registrado')}</strong></div>
            </div>

            <div class="sheet-notes">
                <span>Motivo / observaciones</span>
                <p>${escapeHtml(cita.motivo || cita.observaciones || 'Sin notas registradas.')}</p>
            </div>

            <div class="sheet-actions">
                ${wa ? `<a href="${escapeHtml(wa)}" target="_blank" rel="noopener">WhatsApp</a>` : ''}
                <button type="button" data-close-sheet>Cerrar</button>
            </div>
        `;

        sheet.hidden = false;
    }

    function capitalizar(texto) {
        texto = String(texto || '');
        return texto.charAt(0).toUpperCase() + texto.slice(1);
    }

    prevDay.addEventListener('click', () => goToDay(activeIndex - 1));
    nextDay.addEventListener('click', () => goToDay(activeIndex + 1));

    prevWeek.addEventListener('click', () => {
        weekStart = sumarDias(weekStart, -7);
        activeIndex = 0;
        cargarSemana();
    });

    nextWeek.addEventListener('click', () => {
        weekStart = sumarDias(weekStart, 7);
        activeIndex = 0;
        cargarSemana();
    });

    buscarInput.addEventListener('input', () => {
        clearTimeout(debounceBuscar);
        debounceBuscar = setTimeout(() => {
            activeIndex = 0;
            cargarSemana();
        }, 380);
    });

    let scrollTimer = null;

    carousel.addEventListener('scroll', () => {
        clearTimeout(scrollTimer);

        scrollTimer = setTimeout(() => {
            const index = Math.round(carousel.scrollLeft / carousel.clientWidth);
            goToDay(index, false);
        }, 90);
    });

    document.addEventListener('click', event => {
        if (event.target.matches('[data-close-sheet]')) {
            sheet.hidden = true;
        }
    });

    sheet.addEventListener('click', event => {
        if (event.target === sheet) {
            sheet.hidden = true;
        }
    });

    cargarSemana();
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>