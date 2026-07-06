<?php
// pages/citas.php
$pageTitle = 'Citas';
$activePage = 'citas';
$pageKicker = 'Agenda móvil';

// Evita que el servidor muestre mañana por zona horaria distinta a Perú.
$tz = new DateTimeZone('America/Lima');
$hoy = (new DateTimeImmutable('now', $tz))->format('Y-m-d');

require_once __DIR__ . '/../includes/header.php';
?>

<section class="ios-agenda" data-today="<?= e($hoy) ?>">
    <div class="ios-hero-card">
        <div class="ios-hero-top">
            <div>
                <span class="ios-eyebrow">Agenda móvil</span>
                <h1>Citas</h1>
                <p id="subtituloAgenda">Citas de hoy</p>
            </div>

            <a class="ios-fab-mini" href="#" aria-label="Nueva cita">
                <?= appIcon('plus') ?>
            </a>
        </div>

        <label class="ios-search">
            <?= appIcon('search') ?>
            <input
                type="search"
                id="buscarCita"
                placeholder="Buscar paciente, doctor o servicio"
                autocomplete="off"
                inputmode="search">
        </label>

        <div class="ios-date-panel">
            <button type="button" class="ios-date-main" id="btnHoy">
                <span id="diaGrande">Hoy</span>
                <strong id="fechaHumana">--</strong>
            </button>

            <label class="ios-date-picker">
                <span>Cambiar</span>
                <input type="date" id="fechaActiva" value="<?= e($hoy) ?>">
            </label>
        </div>
    </div>

    <div class="ios-filter-dock" aria-label="Filtros de agenda">
        <div class="ios-range-pill" role="tablist" aria-label="Rango de citas">
            <button type="button" class="is-active" data-alcance="dia">Hoy</button>
            <button type="button" data-alcance="semana">7 días</button>
            <button type="button" data-alcance="todas">Todas</button>
        </div>

        <button type="button" class="ios-filter-pill" id="btnEstadoFiltro" aria-label="Filtrar por estado">
            <span>Estado</span>
            <strong id="estadoFiltroTexto">Todas</strong>
        </button>
    </div>

    <div class="ios-feed-head">
        <div>
            <strong id="contadorCitas">Cargando...</strong>
            <span id="detalleRango">Consultando agenda</span>
        </div>
        <button type="button" id="btnActualizarCitas">Actualizar</button>
    </div>

    <section class="ios-list" id="listaCitas" aria-live="polite">
        <article class="ios-skeleton-card">
            <span></span>
            <div>
                <b></b>
                <p></p>
                <p></p>
            </div>
        </article>
        <article class="ios-skeleton-card">
            <span></span>
            <div>
                <b></b>
                <p></p>
                <p></p>
            </div>
        </article>
    </section>

    <div class="ios-loader-more" id="loaderMas" hidden>
        <span></span>
        <p>Cargando más citas...</p>
    </div>

    <div class="ios-end-feed" id="finLista" hidden>
        No hay más citas para mostrar
    </div>

    <div id="sentinelaCitas" class="ios-sentinel" aria-hidden="true"></div>
</section>

<div class="ios-sheet-backdrop" id="detalleSheet" hidden>
    <section class="ios-sheet" role="dialog" aria-modal="true" aria-labelledby="detalleTitulo">
        <button type="button" class="ios-sheet-handle" data-close-sheet aria-label="Cerrar detalle"></button>
        <div id="detalleContenido"></div>
    </section>
</div>

<div class="ios-sheet-backdrop" id="estadoSheet" hidden>
    <section class="ios-sheet ios-filter-sheet" role="dialog" aria-modal="true" aria-labelledby="estadoSheetTitulo">
        <button type="button" class="ios-sheet-handle" data-close-estado-sheet aria-label="Cerrar filtros"></button>
        <div class="ios-filter-sheet-head">
            <h2 id="estadoSheetTitulo">Filtrar estado</h2>
            <p>Elige qué citas quieres ver.</p>
        </div>
        <div class="ios-status-grid" aria-label="Estados de cita">
            <button type="button" class="is-active" data-estado="0" data-label="Todas">Todas</button>
            <button type="button" data-estado="1" data-label="Pendientes">Pendientes</button>
            <button type="button" data-estado="2" data-label="Confirmadas">Confirmadas</button>
            <button type="button" data-estado="3" data-label="Atendidas">Atendidas</button>
            <button type="button" data-estado="4" data-label="Canceladas">Canceladas</button>
            <button type="button" data-estado="5" data-label="No asistió">No asistió</button>
        </div>
    </section>
</div>

<style>
    :root {
        --ios-bg: #f3f7fb;
        --ios-card: rgba(255, 255, 255, .92);
        --ios-line: rgba(15, 23, 42, .08);
        --ios-text: #101828;
        --ios-muted: #667085;
        --ios-primary: var(--primario, #0ea5b7);
        --ios-radius-xl: 28px;
        --ios-radius-lg: 22px;
        --ios-shadow: 0 24px 60px rgba(15, 23, 42, .10);
    }

    .ios-agenda {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding-bottom: calc(96px + env(safe-area-inset-bottom));
    }

    .ios-hero-card,
    .ios-list article,
    .ios-feed-head,
    .ios-sheet {
        -webkit-backdrop-filter: blur(18px);
        backdrop-filter: blur(18px);
    }

    .ios-hero-card {
        border: 1px solid rgba(255,255,255,.74);
        border-radius: var(--ios-radius-xl);
        padding: 20px;
        background:
            radial-gradient(circle at 15% 0%, rgba(14, 165, 183, .18), transparent 34%),
            linear-gradient(180deg, rgba(255,255,255,.96), rgba(255,255,255,.82));
        box-shadow: var(--ios-shadow);
    }

    .ios-hero-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
    }

    .ios-eyebrow {
        display: inline-flex;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .04em;
        color: var(--ios-primary);
        text-transform: uppercase;
    }

    .ios-hero-card h1 {
        margin: 0;
        font-size: clamp(30px, 8vw, 42px);
        line-height: .96;
        letter-spacing: -.05em;
        color: var(--ios-text);
    }

    .ios-hero-card p {
        margin: 8px 0 0;
        color: var(--ios-muted);
        font-weight: 700;
    }

    .ios-fab-mini {
        width: 48px;
        height: 48px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: linear-gradient(135deg, var(--ios-primary), #38bdf8);
        box-shadow: 0 16px 36px rgba(14, 165, 183, .30);
        text-decoration: none;
        flex: 0 0 auto;
    }

    .ios-fab-mini svg {
        width: 22px;
        height: 22px;
    }

    .ios-search {
        min-height: 54px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 16px;
        background: rgba(255,255,255,.88);
        border: 1px solid rgba(15,23,42,.07);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.86);
    }

    .ios-search svg {
        width: 20px;
        height: 20px;
        color: #98a2b3;
        flex: 0 0 auto;
    }

    .ios-search input {
        width: 100%;
        min-width: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: var(--ios-text);
        font-size: 15px;
        font-weight: 750;
    }

    .ios-search input::placeholder {
        color: #98a2b3;
    }

    .ios-date-panel {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        margin-top: 12px;
    }

    .ios-date-main,
    .ios-date-picker {
        border: 1px solid rgba(15,23,42,.07);
        border-radius: 20px;
        background: rgba(255,255,255,.78);
        min-height: 62px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.9);
    }

    .ios-date-main {
        text-align: left;
        padding: 10px 14px;
        cursor: pointer;
    }

    .ios-date-main span,
    .ios-date-picker span {
        display: block;
        font-size: 11px;
        color: #667085;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .ios-date-main strong {
        display: block;
        margin-top: 3px;
        color: var(--ios-text);
        font-size: 17px;
        letter-spacing: -.02em;
    }

    .ios-date-picker {
        position: relative;
        min-width: 118px;
        padding: 10px 12px;
        overflow: hidden;
    }

    .ios-date-picker input {
        width: 100%;
        margin-top: 4px;
        border: 0;
        outline: 0;
        background: transparent;
        font-size: 13px;
        color: var(--ios-primary);
        font-weight: 900;
        appearance: none;
    }

    .ios-segment,
    .ios-status-strip {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        padding: 2px 2px 4px;
    }

    .ios-segment::-webkit-scrollbar,
    .ios-status-strip::-webkit-scrollbar {
        display: none;
    }

    .ios-segment {
        position: sticky;
        top: 0;
        z-index: 12;
        padding-top: 8px;
        background: linear-gradient(180deg, var(--ios-bg), rgba(243,247,251,.84));
        -webkit-backdrop-filter: blur(12px);
        backdrop-filter: blur(12px);
    }

    .ios-segment button,
    .ios-status-strip button {
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 999px;
        background: rgba(255,255,255,.88);
        color: #475467;
        font-size: 13px;
        font-weight: 900;
        white-space: nowrap;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(15,23,42,.04);
    }

    .ios-segment button {
        height: 42px;
        padding: 0 18px;
    }

    .ios-status-strip button {
        height: 38px;
        padding: 0 14px;
        font-size: 12px;
    }

    .ios-segment button.is-active,
    .ios-status-strip button.is-active {
        border-color: transparent;
        color: #fff;
        background: linear-gradient(135deg, var(--ios-primary), #38bdf8);
        box-shadow: 0 16px 34px rgba(14,165,183,.24);
    }

    .ios-feed-head {
        border: 1px solid rgba(255,255,255,.74);
        border-radius: 22px;
        padding: 14px 16px;
        background: rgba(255,255,255,.74);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .ios-feed-head strong,
    .ios-feed-head span {
        display: block;
    }

    .ios-feed-head strong {
        font-size: 15px;
        color: var(--ios-text);
        letter-spacing: -.02em;
    }

    .ios-feed-head span {
        margin-top: 2px;
        color: var(--ios-muted);
        font-size: 12px;
        font-weight: 750;
    }

    .ios-feed-head button {
        border: 0;
        border-radius: 999px;
        padding: 10px 14px;
        color: var(--ios-primary);
        background: rgba(14,165,183,.10);
        font-size: 12px;
        font-weight: 900;
        cursor: pointer;
    }

    .ios-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ios-appointment {
        position: relative;
        display: grid;
        grid-template-columns: 72px 1fr;
        gap: 14px;
        padding: 14px;
        border-radius: 24px;
        border: 1px solid rgba(255,255,255,.78);
        background: rgba(255,255,255,.88);
        box-shadow: 0 18px 44px rgba(15,23,42,.07);
        cursor: pointer;
        overflow: hidden;
    }

    .ios-appointment:active {
        transform: scale(.99);
    }

    .ios-time-block {
        display: flex;
        min-height: 76px;
        border-radius: 20px;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: linear-gradient(180deg, rgba(14,165,183,.12), rgba(14,165,183,.05));
        border: 1px solid rgba(14,165,183,.14);
        color: var(--ios-primary);
    }

    .ios-time-block strong {
        font-size: 18px;
        line-height: 1;
        letter-spacing: -.04em;
    }

    .ios-time-block span {
        margin-top: 4px;
        font-size: 10px;
        font-weight: 950;
        letter-spacing: .08em;
    }

    .ios-card-body {
        min-width: 0;
    }

    .ios-card-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        align-items: flex-start;
    }

    .ios-card-body h3 {
        margin: 0;
        color: var(--ios-text);
        font-size: 16px;
        line-height: 1.15;
        letter-spacing: -.03em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ios-service {
        margin: 5px 0 0;
        color: #344054;
        font-size: 13px;
        font-weight: 850;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ios-doctor,
    .ios-motive {
        margin: 5px 0 0;
        color: var(--ios-muted);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ios-status {
        --status-color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 116px;
        min-height: 28px;
        padding: 0 10px;
        border-radius: 999px;
        border: 1px solid color-mix(in srgb, var(--status-color) 25%, transparent);
        background: color-mix(in srgb, var(--status-color) 12%, white);
        color: color-mix(in srgb, var(--status-color) 74%, #0f172a);
        font-size: 10px;
        font-weight: 950;
        letter-spacing: .02em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 0 0 auto;
    }

    .ios-status::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--status-color);
        flex: 0 0 auto;
    }

    .ios-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
    }

    .ios-date-small {
        color: #98a2b3;
        font-size: 11px;
        font-weight: 850;
    }

    .ios-whatsapp {
        border-radius: 999px;
        padding: 7px 10px;
        background: rgba(16,185,129,.10);
        color: #047857;
        text-decoration: none;
        font-size: 11px;
        font-weight: 950;
    }

    .ios-empty,
    .ios-error {
        padding: 32px 20px;
        border-radius: 26px;
        border: 1px solid rgba(255,255,255,.78);
        background: rgba(255,255,255,.86);
        box-shadow: 0 18px 44px rgba(15,23,42,.07);
        text-align: center;
    }

    .ios-empty-icon {
        width: 56px;
        height: 56px;
        border-radius: 20px;
        margin: 0 auto 14px;
        display: grid;
        place-items: center;
        background: rgba(14,165,183,.10);
        color: var(--ios-primary);
        font-size: 24px;
    }

    .ios-empty h3,
    .ios-error h3 {
        margin: 0;
        color: var(--ios-text);
        font-size: 18px;
        letter-spacing: -.03em;
    }

    .ios-empty p,
    .ios-error p {
        margin: 7px 0 0;
        color: var(--ios-muted);
        font-size: 13px;
        font-weight: 700;
    }

    .ios-error .ios-empty-icon {
        background: rgba(239,68,68,.10);
        color: #dc2626;
    }

    .ios-skeleton-card {
        display: grid;
        grid-template-columns: 72px 1fr;
        gap: 14px;
        padding: 14px;
        border-radius: 24px;
        background: rgba(255,255,255,.78);
        border: 1px solid rgba(255,255,255,.8);
    }

    .ios-skeleton-card span,
    .ios-skeleton-card b,
    .ios-skeleton-card p {
        display: block;
        border-radius: 999px;
        background: linear-gradient(90deg, #eef2f7, #f8fafc, #eef2f7);
        background-size: 220% 100%;
        animation: iosSkeleton 1.4s ease-in-out infinite;
    }

    .ios-skeleton-card span {
        width: 72px;
        height: 76px;
        border-radius: 20px;
    }

    .ios-skeleton-card b {
        height: 18px;
        width: 68%;
        margin-top: 4px;
    }

    .ios-skeleton-card p {
        height: 12px;
        width: 92%;
        margin: 12px 0 0;
    }

    .ios-skeleton-card p:last-child {
        width: 48%;
    }

    @keyframes iosSkeleton {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
    }

    .ios-loader-more {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: var(--ios-muted);
        font-size: 12px;
        font-weight: 850;
        min-height: 42px;
    }

    .ios-loader-more span {
        width: 16px;
        height: 16px;
        border-radius: 999px;
        border: 2px solid rgba(14,165,183,.18);
        border-top-color: var(--ios-primary);
        animation: iosSpin .8s linear infinite;
    }

    .ios-loader-more p {
        margin: 0;
    }

    @keyframes iosSpin {
        to { transform: rotate(360deg); }
    }

    .ios-end-feed {
        text-align: center;
        color: #98a2b3;
        font-size: 12px;
        font-weight: 850;
        padding: 8px 0 0;
    }

    .ios-sentinel {
        height: 1px;
    }

    .ios-sheet-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: flex-end;
        background: rgba(15,23,42,.28);
        -webkit-backdrop-filter: blur(8px);
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
        border: 1px solid rgba(255,255,255,.74);
        background: rgba(255,255,255,.96);
        box-shadow: 0 -24px 60px rgba(15,23,42,.22);
        padding: 10px 18px 18px;
        animation: sheetUp .22s ease-out;
    }

    @keyframes sheetUp {
        from { transform: translateY(26px); opacity: .6; }
        to { transform: translateY(0); opacity: 1; }
    }

    .ios-sheet-handle {
        display: block;
        width: 46px;
        height: 5px;
        border: 0;
        border-radius: 999px;
        background: #d0d5dd;
        margin: 2px auto 16px;
        cursor: pointer;
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
        color: var(--ios-text);
        font-size: 22px;
        letter-spacing: -.04em;
        line-height: 1.05;
    }

    .sheet-title p {
        margin: 6px 0 0;
        color: var(--ios-muted);
        font-size: 13px;
        font-weight: 750;
    }

    .sheet-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .sheet-item {
        border-radius: 18px;
        border: 1px solid rgba(15,23,42,.06);
        background: #f8fafc;
        padding: 12px;
    }

    .sheet-item span {
        display: block;
        color: #98a2b3;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .sheet-item strong {
        display: block;
        margin-top: 4px;
        color: var(--ios-text);
        font-size: 13px;
        line-height: 1.2;
    }

    .sheet-notes {
        margin-top: 10px;
        border-radius: 18px;
        border: 1px solid rgba(15,23,42,.06);
        background: #f8fafc;
        padding: 12px;
    }

    .sheet-notes span {
        color: #98a2b3;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .sheet-notes p {
        margin: 5px 0 0;
        color: #344054;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
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
        cursor: pointer;
    }

    .sheet-actions a {
        color: #047857;
        background: rgba(16,185,129,.12);
    }

    .sheet-actions button {
        color: #fff;
        background: linear-gradient(135deg, var(--ios-primary), #38bdf8);
    }

    @media (min-width: 760px) {
        .ios-agenda {
            max-width: 760px;
            margin: 0 auto;
        }

        .ios-sheet-backdrop {
            justify-content: center;
        }

        .ios-sheet {
            max-width: 560px;
        }
    }

    @media (max-width: 420px) {
        .ios-hero-card {
            padding: 18px;
            border-radius: 26px;
        }

        .ios-date-panel {
            grid-template-columns: 1fr;
        }

        .ios-date-picker {
            min-width: 0;
        }

        .ios-appointment,
        .ios-skeleton-card {
            grid-template-columns: 64px 1fr;
            gap: 12px;
            padding: 12px;
        }

        .ios-time-block,
        .ios-skeleton-card span {
            width: 64px;
        }

        .sheet-grid {
            grid-template-columns: 1fr;
        }
    }


    .ios-filter-dock {
        position: sticky;
        top: 0;
        z-index: 12;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        align-items: center;
        padding: 8px 0 6px;
        background: linear-gradient(180deg, var(--ios-bg), rgba(243,247,251,.86));
        -webkit-backdrop-filter: blur(14px);
        backdrop-filter: blur(14px);
    }

    .ios-range-pill {
        min-height: 48px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 4px;
        padding: 5px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,.78);
        background: rgba(255,255,255,.72);
        box-shadow: 0 12px 26px rgba(15,23,42,.06), inset 0 1px 0 rgba(255,255,255,.9);
    }

    .ios-range-pill button {
        border: 0;
        border-radius: 999px;
        background: transparent;
        color: #667085;
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
        cursor: pointer;
    }

    .ios-range-pill button.is-active {
        color: #fff;
        background: linear-gradient(135deg, var(--ios-primary), #38bdf8);
        box-shadow: 0 12px 26px rgba(14,165,183,.24);
    }

    .ios-filter-pill {
        min-height: 48px;
        min-width: 108px;
        border: 1px solid rgba(255,255,255,.78);
        border-radius: 18px;
        background: rgba(255,255,255,.82);
        box-shadow: 0 12px 26px rgba(15,23,42,.06), inset 0 1px 0 rgba(255,255,255,.92);
        padding: 7px 12px;
        text-align: left;
        cursor: pointer;
    }

    .ios-filter-pill span,
    .ios-filter-sheet-head p {
        display: block;
        color: #98a2b3;
        font-size: 10px;
        font-weight: 950;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .ios-filter-pill strong {
        display: block;
        margin-top: 2px;
        max-width: 92px;
        color: var(--ios-primary);
        font-size: 12px;
        font-weight: 950;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .ios-filter-sheet-head {
        margin-bottom: 14px;
    }

    .ios-filter-sheet-head h2 {
        margin: 0;
        color: var(--ios-text);
        font-size: 22px;
        letter-spacing: -.04em;
    }

    .ios-filter-sheet-head p {
        margin: 6px 0 0;
        text-transform: none;
        letter-spacing: 0;
        font-size: 13px;
        color: var(--ios-muted);
    }

    .ios-status-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .ios-status-grid button {
        min-height: 48px;
        border: 1px solid rgba(15,23,42,.07);
        border-radius: 17px;
        background: #f8fafc;
        color: #344054;
        font-size: 13px;
        font-weight: 950;
        cursor: pointer;
    }

    .ios-status-grid button.is-active {
        border-color: transparent;
        color: #fff;
        background: linear-gradient(135deg, var(--ios-primary), #38bdf8);
        box-shadow: 0 14px 30px rgba(14,165,183,.24);
    }

    .ios-loader-more[hidden],
    .ios-end-feed[hidden] {
        display: none !important;
    }

    @media (max-width: 420px) {
        .ios-filter-dock {
            grid-template-columns: 1fr;
        }

        .ios-filter-pill {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: left;
        }

        .ios-filter-pill strong {
            max-width: 160px;
        }
    }


    /* Scroll estable iOS PWA: no se bloquea el body para evitar congelamientos en Safari standalone */
    html,
    body {
        overscroll-behavior-y: auto;
    }

    .ios-sheet-backdrop {
        overscroll-behavior: contain;
        touch-action: auto;
    }

    .ios-sheet {
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
    }

    .ios-sentinel {
        height: 72px;
        min-height: 72px;
        pointer-events: none;
    }

</style>

<script>
(function () {
    'use strict';

    const API_URL = 'https://app.ortodonciaclinica.pe/api/citas/listar.php';
    const PER_PAGE = 12;

    const root = document.querySelector('.ios-agenda');
    const lista = document.getElementById('listaCitas');
    const buscar = document.getElementById('buscarCita');
    const fechaActiva = document.getElementById('fechaActiva');
    const btnHoy = document.getElementById('btnHoy');
    const btnActualizar = document.getElementById('btnActualizarCitas');
    const btnEstadoFiltro = document.getElementById('btnEstadoFiltro');
    const estadoFiltroTexto = document.getElementById('estadoFiltroTexto');
    const contadorCitas = document.getElementById('contadorCitas');
    const detalleRango = document.getElementById('detalleRango');
    const subtituloAgenda = document.getElementById('subtituloAgenda');
    const fechaHumana = document.getElementById('fechaHumana');
    const diaGrande = document.getElementById('diaGrande');
    const loaderMas = document.getElementById('loaderMas');
    const finLista = document.getElementById('finLista');
    const sentinela = document.getElementById('sentinelaCitas');
    const sheet = document.getElementById('detalleSheet');
    const estadoSheet = document.getElementById('estadoSheet');
    const detalleContenido = document.getElementById('detalleContenido');

    const estado = {
        fecha: fechaLocalActual(),
        alcance: 'dia',
        estadoId: '0',
        estadoLabel: 'Todas',
        buscar: '',
        pagina: 0,
        totalPaginas: 1,
        total: 0,
        cargando: false,
        hasMore: true,
        items: []
    };

    let debounceBuscar = null;
    let controller = null;
    let rafScroll = null;
    let cooldownCargaMas = false;
    let observer = null;

    function limpiarBloqueoScrollLegacy() {
        document.documentElement.classList.remove('ios-scroll-locked');
        document.body.classList.remove('ios-scroll-locked');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.width = '';
        document.body.style.touchAction = '';
    }

    function bloquearScroll() {
        // En iOS PWA, bloquear el body con position:fixed puede congelar el scroll.
        // Dejamos el documento intacto y solo mostramos el sheet fijo.
        limpiarBloqueoScrollLegacy();
    }

    function desbloquearScroll() {
        limpiarBloqueoScrollLegacy();
        requestAnimationFrame(() => {
            limpiarBloqueoScrollLegacy();
            revisarScrollCarga();
        });
    }

    limpiarBloqueoScrollLegacy();

    function subirAlInicioAgenda() {
        if (!root) return;

        const top = Math.max(0, root.getBoundingClientRect().top + window.scrollY - 8);
        window.scrollTo({ top, behavior: 'auto' });
    }

    function scrollYActual() {
        return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
    }

    function altoViewport() {
        return window.visualViewport ? window.visualViewport.height : window.innerHeight;
    }

    function puedeCargarMas() {
        if (estado.cargando || !estado.hasMore) return false;
        if (!sheet.hidden || !estadoSheet.hidden) return false;

        const doc = document.documentElement;
        const altoDocumento = Math.max(
            document.body.scrollHeight,
            doc.scrollHeight,
            document.body.offsetHeight,
            doc.offsetHeight,
            document.body.clientHeight,
            doc.clientHeight
        );

        const posicion = scrollYActual() + altoViewport();
        return altoDocumento - posicion <= 520;
    }

    function solicitarMasCitas() {
        if (!puedeCargarMas() || cooldownCargaMas) return;

        cooldownCargaMas = true;
        window.setTimeout(() => {
            cooldownCargaMas = false;
        }, 360);

        cargarCitas(false);
    }

    function revisarScrollCarga() {
        if (rafScroll) return;

        rafScroll = window.requestAnimationFrame(() => {
            rafScroll = null;
            solicitarMasCitas();
        });
    }

    fechaActiva.value = estado.fecha;
    if (root) root.dataset.today = estado.fecha;

    function fechaLocalActual() {
        const ahora = new Date();
        const offset = ahora.getTimezoneOffset();
        const local = new Date(ahora.getTime() - (offset * 60000));
        return local.toISOString().slice(0, 10);
    }

    function sumarDias(fechaISO, dias) {
        const partes = fechaISO.split('-').map(Number);
        const fecha = new Date(partes[0], partes[1] - 1, partes[2]);
        fecha.setDate(fecha.getDate() + dias);
        const offset = fecha.getTimezoneOffset();
        const local = new Date(fecha.getTime() - (offset * 60000));
        return local.toISOString().slice(0, 10);
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

    function formatearFecha(fecha) {
        if (!fecha) return '';
        const partes = fecha.split('-');
        if (partes.length !== 3) return fecha;
        return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }

    function fechaLarga(fecha) {
        if (!fecha) return '--';
        const partes = fecha.split('-').map(Number);
        const d = new Date(partes[0], partes[1] - 1, partes[2]);
        return d.toLocaleDateString('es-PE', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });
    }

    function formatearHora(hora) {
        if (!hora) return { hora: '--:--', periodo: '' };
        const partes = hora.split(':');
        let h = parseInt(partes[0], 10);
        const m = partes[1] ?? '00';
        if (Number.isNaN(h)) return { hora: hora, periodo: '' };
        const periodo = h >= 12 ? 'PM' : 'AM';
        let h12 = h % 12;
        if (h12 === 0) h12 = 12;
        return {
            hora: `${String(h12).padStart(2, '0')}:${m}`,
            periodo
        };
    }

    function colorSeguro(color, estadoNombre) {
        const value = String(color ?? '').trim();
        if (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(value)) return value;

        const n = normalizarTexto(estadoNombre);
        if (n.includes('pendiente')) return '#F59E0B';
        if (n.includes('confirmada')) return '#10B981';
        if (n.includes('atendida')) return '#3B82F6';
        if (n.includes('cancelada')) return '#EF4444';
        if (n.includes('no asistio')) return '#6B7280';
        return '#64748B';
    }

    function whatsappUrl(numero, mensaje) {
        const limpio = String(numero ?? '').replace(/\D/g, '');
        if (!limpio) return '';
        const numeroFinal = limpio.startsWith('51') ? limpio : `51${limpio}`;
        return `https://wa.me/${numeroFinal}?text=${encodeURIComponent(mensaje)}`;
    }

    function rangoActual() {
        if (estado.alcance === 'todas') {
            return { inicio: estado.fecha, fin: estado.fecha, alcance: 'todas' };
        }

        if (estado.alcance === 'semana') {
            return { inicio: estado.fecha, fin: sumarDias(estado.fecha, 6), alcance: 'rango' };
        }

        return { inicio: estado.fecha, fin: estado.fecha, alcance: 'rango' };
    }

    function actualizarCabecera() {
        const hoy = fechaLocalActual();
        const esHoy = estado.fecha === hoy;
        const fechaTexto = fechaLarga(estado.fecha);

        diaGrande.textContent = esHoy ? 'Hoy' : 'Fecha';
        fechaHumana.textContent = fechaTexto.charAt(0).toUpperCase() + fechaTexto.slice(1);
        estadoFiltroTexto.textContent = estado.estadoLabel;

        if (estado.alcance === 'todas') {
            subtituloAgenda.textContent = 'Todas las citas';
            detalleRango.textContent = estado.buscar ? 'Búsqueda en toda la agenda' : 'Agenda completa';
            return;
        }

        if (estado.alcance === 'semana') {
            subtituloAgenda.textContent = esHoy ? 'Próximos 7 días' : '7 días desde la fecha elegida';
            detalleRango.textContent = `${formatearFecha(estado.fecha)} al ${formatearFecha(sumarDias(estado.fecha, 6))}`;
            return;
        }

        subtituloAgenda.textContent = esHoy ? 'Citas de hoy' : 'Citas del día';
        detalleRango.textContent = formatearFecha(estado.fecha);
    }

    function pintarSkeleton() {
        lista.innerHTML = `
            <article class="ios-skeleton-card"><span></span><div><b></b><p></p><p></p></div></article>
            <article class="ios-skeleton-card"><span></span><div><b></b><p></p><p></p></div></article>
            <article class="ios-skeleton-card"><span></span><div><b></b><p></p><p></p></div></article>
        `;
    }

    function tarjetaCita(cita) {
        const hora = formatearHora(cita.hora_inicio);
        const estadoNombre = cita.estado || 'Sin estado';
        const estadoColor = colorSeguro(cita.color, estadoNombre);
        const paciente = cita.paciente || 'Paciente sin nombre';
        const servicio = cita.servicio || 'Sin servicio';
        const doctor = cita.doctor || 'Sin doctor';
        const motivo = cita.motivo || '';
        const fecha = formatearFecha(cita.fecha_cita);
        const mensaje = `Hola ${paciente}, te recordamos tu cita dental para el ${fecha} a las ${hora.hora} ${hora.periodo}. Te esperamos.`;
        const wa = whatsappUrl(cita.whatsapp, mensaje);

        return `
            <article class="ios-appointment" data-cita='${escapeHtml(JSON.stringify(cita))}'>
                <div class="ios-time-block">
                    <strong>${escapeHtml(hora.hora)}</strong>
                    <span>${escapeHtml(hora.periodo)}</span>
                </div>

                <div class="ios-card-body">
                    <div class="ios-card-row">
                        <div style="min-width:0">
                            <h3>${escapeHtml(paciente)}</h3>
                            <p class="ios-service">${escapeHtml(servicio)}</p>
                        </div>
                        <span class="ios-status" style="--status-color:${escapeHtml(estadoColor)}">${escapeHtml(estadoNombre)}</span>
                    </div>

                    <p class="ios-doctor">${escapeHtml(doctor)}</p>
                    ${motivo ? `<p class="ios-motive">${escapeHtml(motivo)}</p>` : ''}

                    <div class="ios-card-footer">
                        <span class="ios-date-small">${escapeHtml(fecha)}</span>
                        ${wa ? `<a class="ios-whatsapp" href="${escapeHtml(wa)}" target="_blank" rel="noopener" onclick="event.stopPropagation()">WhatsApp</a>` : ''}
                    </div>
                </div>
            </article>
        `;
    }

    function pintarLista(reset, nuevos = []) {
        if (!estado.items.length) {
            lista.innerHTML = `
                <article class="ios-empty">
                    <div class="ios-empty-icon">⌁</div>
                    <h3>No hay citas para mostrar</h3>
                    <p>Prueba con otra fecha, estado o búsqueda.</p>
                </article>
            `;
            finLista.hidden = true;
            return;
        }

        if (reset) {
            lista.innerHTML = estado.items.map(tarjetaCita).join('');
        } else if (nuevos.length) {
            lista.insertAdjacentHTML('beforeend', nuevos.map(tarjetaCita).join(''));
        }

        finLista.hidden = estado.hasMore || estado.items.length <= PER_PAGE;
    }

    function actualizarContador() {
        if (estado.cargando && estado.pagina <= 1 && !estado.items.length) {
            contadorCitas.textContent = 'Cargando...';
            return;
        }

        if (estado.total === 0) {
            contadorCitas.textContent = '0 citas';
            return;
        }

        const visibles = Math.min(estado.items.length, estado.total);
        contadorCitas.textContent = `${visibles} de ${estado.total} citas`;
    }

    function aplicarPaginacion(nuevos, paginacion, paginaSolicitada) {
        const total = Number(paginacion.total);
        const totalPaginas = Number(paginacion.total_paginas);

        if (Number.isFinite(total) && total >= 0) {
            estado.total = total;
        } else if (paginaSolicitada === 1) {
            estado.total = nuevos.length;
        }

        if (Number.isFinite(totalPaginas) && totalPaginas > 0) {
            estado.totalPaginas = totalPaginas;
            estado.hasMore = paginaSolicitada < totalPaginas;
        } else {
            estado.totalPaginas = paginaSolicitada;
            estado.hasMore = nuevos.length === PER_PAGE;
        }

        if (!nuevos.length || nuevos.length < PER_PAGE) {
            estado.hasMore = false;
        }

        if (estado.total > 0 && estado.items.length >= estado.total) {
            estado.hasMore = false;
        }
    }

    async function cargarCitas(reset = true) {
        if (estado.cargando) return;
        if (!reset && !estado.hasMore) return;

        const paginaSolicitada = reset ? 1 : estado.pagina + 1;

        estado.cargando = true;
        loaderMas.hidden = reset;
        finLista.hidden = true;

        if (reset) {
            estado.pagina = 0;
            estado.totalPaginas = 1;
            estado.total = 0;
            estado.hasMore = true;
            estado.items = [];
            pintarSkeleton();
            if (controller) controller.abort();
            controller = new AbortController();
        }

        actualizarCabecera();
        actualizarContador();

        try {
            const rango = rangoActual();
            const url = new URL(API_URL, window.location.href);

            url.searchParams.set('inicio', rango.inicio);
            url.searchParams.set('fin', rango.fin);
            url.searchParams.set('alcance', rango.alcance);
            url.searchParams.set('estado', estado.estadoId);
            url.searchParams.set('buscar', estado.buscar);
            url.searchParams.set('pagina', String(paginaSolicitada));
            url.searchParams.set('por_pagina', String(PER_PAGE));

            const response = await fetch(url.toString(), {
                method: 'GET',
                credentials: 'include',
                headers: { 'Accept': 'application/json' },
                signal: controller ? controller.signal : undefined
            });

            const texto = await response.text();
            let json;

            try {
                json = JSON.parse(texto);
            } catch (error) {
                console.error('Respuesta no JSON del API:', texto);
                throw new Error('El API no devolvió JSON.');
            }

            if (!response.ok || !json.ok) {
                console.error('Error del API:', json);
                throw new Error(json.mensaje || json.error || 'No se pudo cargar la información.');
            }

            const nuevos = Array.isArray(json.data) ? json.data : [];
            const paginacion = json.paginacion || {};

            estado.pagina = paginaSolicitada;
            estado.items = reset ? nuevos : estado.items.concat(nuevos);
            aplicarPaginacion(nuevos, paginacion, paginaSolicitada);

            pintarLista(reset, nuevos);
            actualizarContador();

        } catch (error) {
            if (error.name === 'AbortError') return;
            console.error(error);

            estado.hasMore = false;
            if (reset) {
                lista.innerHTML = `
                    <article class="ios-error">
                        <div class="ios-empty-icon">!</div>
                        <h3>No se pudieron cargar las citas</h3>
                        <p>Verifica la sesión, conexión o el API de citas.</p>
                    </article>
                `;
            }
        } finally {
            estado.cargando = false;
            loaderMas.hidden = true;
            actualizarCabecera();
            actualizarContador();

            // Revisa una sola vez si la pantalla quedó corta; no bloquea ni fuerza scroll.
            window.setTimeout(revisarScrollCarga, 180);
        }
    }

    function resetYCargar() {
        estado.hasMore = true;
        subirAlInicioAgenda();
        cargarCitas(true);
    }

    function activarBoton(grupoSelector, boton) {
        document.querySelectorAll(grupoSelector).forEach(item => item.classList.remove('is-active'));
        boton.classList.add('is-active');
    }

    document.querySelectorAll('[data-alcance]').forEach(btn => {
        btn.addEventListener('click', () => {
            estado.alcance = btn.dataset.alcance || 'dia';
            activarBoton('[data-alcance]', btn);
            resetYCargar();
        });
    });

    btnEstadoFiltro.addEventListener('click', () => {
        estadoSheet.hidden = false;
        bloquearScroll();
    });

    document.querySelectorAll('[data-estado]').forEach(btn => {
        btn.addEventListener('click', () => {
            estado.estadoId = btn.dataset.estado || '0';
            estado.estadoLabel = btn.dataset.label || btn.textContent.trim() || 'Todas';
            activarBoton('[data-estado]', btn);
            estadoSheet.hidden = true;
            desbloquearScroll();
            resetYCargar();
        });
    });

    buscar.addEventListener('input', () => {
        clearTimeout(debounceBuscar);
        debounceBuscar = setTimeout(() => {
            estado.buscar = buscar.value.trim();
            resetYCargar();
        }, 380);
    });

    fechaActiva.addEventListener('change', () => {
        estado.fecha = fechaActiva.value || fechaLocalActual();
        estado.alcance = 'dia';
        document.querySelectorAll('[data-alcance]').forEach(item => item.classList.remove('is-active'));
        document.querySelector('[data-alcance="dia"]').classList.add('is-active');
        resetYCargar();
    });

    btnHoy.addEventListener('click', () => {
        estado.fecha = fechaLocalActual();
        fechaActiva.value = estado.fecha;
        estado.alcance = 'dia';
        document.querySelectorAll('[data-alcance]').forEach(item => item.classList.remove('is-active'));
        document.querySelector('[data-alcance="dia"]').classList.add('is-active');
        resetYCargar();
    });

    btnActualizar.addEventListener('click', resetYCargar);

    lista.addEventListener('click', event => {
        const card = event.target.closest('.ios-appointment');
        if (!card) return;

        let cita = null;

        try {
            cita = JSON.parse(card.dataset.cita || '{}');
        } catch (error) {
            return;
        }

        abrirDetalle(cita);
    });

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
                <span class="ios-status" style="--status-color:${escapeHtml(estadoColor)}">${escapeHtml(estadoNombre)}</span>
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
        bloquearScroll();
    }

    document.addEventListener('click', event => {
        if (event.target.matches('[data-close-sheet]')) {
            sheet.hidden = true;
            desbloquearScroll();
        }

        if (event.target.matches('[data-close-estado-sheet]')) {
            estadoSheet.hidden = true;
            desbloquearScroll();
        }
    });

    sheet.addEventListener('click', event => {
        if (event.target === sheet) {
            sheet.hidden = true;
            desbloquearScroll();
        }
    });

    estadoSheet.addEventListener('click', event => {
        if (event.target === estadoSheet) {
            estadoSheet.hidden = true;
            desbloquearScroll();
        }
    });

    if ('IntersectionObserver' in window && sentinela) {
        observer = new IntersectionObserver(entries => {
            const entry = entries[0];

            if (!entry || !entry.isIntersecting) return;
            solicitarMasCitas();
        }, {
            root: null,
            rootMargin: '0px 0px 360px 0px',
            threshold: 0.01
        });

        observer.observe(sentinela);
    }

    window.addEventListener('scroll', revisarScrollCarga, { passive: true });
    window.addEventListener('resize', revisarScrollCarga);

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', revisarScrollCarga, { passive: true });
        window.visualViewport.addEventListener('scroll', revisarScrollCarga, { passive: true });
    }

    window.addEventListener('orientationchange', () => {
        limpiarBloqueoScrollLegacy();
        window.setTimeout(revisarScrollCarga, 250);
    });

    window.addEventListener('pageshow', () => {
        limpiarBloqueoScrollLegacy();
        revisarScrollCarga();
    });

    actualizarCabecera();
    cargarCitas(true);
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
