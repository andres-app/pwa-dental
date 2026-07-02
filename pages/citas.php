<?php
// pages/citas.php
$pageTitle = 'Citas';
$activePage = 'citas';
$pageKicker = 'Control de citas';

$hoy = date('Y-m-d');
$finDefault = date('Y-m-d', strtotime('+30 days'));

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title-card">
    <h1>Citas</h1>
    <p>Listado de citas registradas en el sistema, con búsqueda, fechas y estados visibles.</p>
</section>

<div class="toolbar">
    <label class="search-box">
        <?= appIcon('search') ?>
        <input
            type="search"
            id="buscarCita"
            placeholder="Buscar por paciente, doctor, servicio o estado"
            autocomplete="off">
    </label>

    <input
        type="date"
        id="fechaInicio"
        class="input-date"
        value="<?= e($hoy) ?>">

    <input
        type="date"
        id="fechaFin"
        class="input-date"
        value="<?= e($finDefault) ?>">

    <button type="button" class="btn btn-soft" id="btnActualizarCitas">
        Actualizar
    </button>

    <a class="btn btn-soft" href="#">
        <?= appIcon('plus') ?> Nueva cita
    </a>
</div>

<section class="list-stack" id="listaCitas">
    <article class="appointment-card">
        <span class="time-chip">--:--<small></small></span>
        <div class="card-copy">
            <h3>Cargando citas...</h3>
            <p>Consultando información registrada.</p>
        </div>
        <div class="card-meta">
            <span class="status-pill">Cargando</span>
        </div>
    </article>
</section>

<style>
    .input-date {
        min-height: 44px;
        border: 1px solid rgba(15, 23, 42, .10);
        border-radius: 14px;
        padding: 0 12px;
        background: #fff;
        color: #0f172a;
        font: inherit;
        outline: none;
    }

    .empty-state-card {
        justify-content: center;
        text-align: center;
        padding: 28px 18px;
    }

    .empty-state-card .card-copy h3 {
        margin-bottom: 6px;
    }

    .appointment-card.is-clickable {
        cursor: pointer;
    }

    .appointment-card.is-clickable:hover {
        transform: translateY(-1px);
    }

    .status-pill.is-danger {
        background: rgba(239, 68, 68, .10);
        color: #b91c1c;
    }
</style>

<script>
    (function() {
        'use strict';

        const API_URL = 'https://app.ortodonciaclinica.pe/api/citas/listar.php';

        const lista = document.getElementById('listaCitas');
        const buscar = document.getElementById('buscarCita');
        const fechaInicio = document.getElementById('fechaInicio');
        const fechaFin = document.getElementById('fechaFin');
        const btnActualizar = document.getElementById('btnActualizarCitas');

        let citasOriginales = [];

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

            if (partes.length !== 3) {
                return fecha;
            }

            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

        function formatearHora(hora) {
            if (!hora) {
                return {
                    hora: '--:--',
                    periodo: ''
                };
            }

            const partes = hora.split(':');
            let h = parseInt(partes[0], 10);
            const m = partes[1] ?? '00';

            if (Number.isNaN(h)) {
                return {
                    hora: hora,
                    periodo: ''
                };
            }

            const periodo = h >= 12 ? 'PM' : 'AM';
            let h12 = h % 12;

            if (h12 === 0) {
                h12 = 12;
            }

            return {
                hora: `${String(h12).padStart(2, '0')}:${m}`,
                periodo: periodo
            };
        }

        function claseEstado(estado) {
            const valor = normalizarTexto(estado);

            if (
                valor.includes('confirmada') ||
                valor.includes('confirmado') ||
                valor.includes('atendida') ||
                valor.includes('completada')
            ) {
                return 'is-success';
            }

            if (
                valor.includes('pendiente') ||
                valor.includes('programada') ||
                valor.includes('reservada')
            ) {
                return 'is-warning';
            }

            if (
                valor.includes('cancelada') ||
                valor.includes('anulada') ||
                valor.includes('no asistio')
            ) {
                return 'is-danger';
            }

            return '';
        }

        function colorSeguro(color) {
            const value = String(color ?? '').trim();

            if (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(value)) {
                return value;
            }

            return '';
        }

        function pintarCitas() {
            const textoBusqueda = normalizarTexto(buscar.value);

            const citasFiltradas = citasOriginales.filter(cita => {
                const texto = normalizarTexto([
                    cita.paciente,
                    cita.doctor,
                    cita.servicio,
                    cita.estado,
                    cita.motivo,
                    cita.fecha_cita
                ].join(' '));

                return texto.includes(textoBusqueda);
            });

            if (!citasFiltradas.length) {
                lista.innerHTML = `
                <article class="appointment-card empty-state-card">
                    <div class="card-copy">
                        <h3>No hay citas para mostrar</h3>
                        <p>Prueba cambiando el rango de fechas o el texto de búsqueda.</p>
                    </div>
                </article>
            `;
                return;
            }

            lista.innerHTML = citasFiltradas.map(cita => {
                const hora = formatearHora(cita.hora_inicio);
                const estado = cita.estado || 'Sin estado';
                const estadoClass = claseEstado(estado);
                const color = colorSeguro(cita.color);

                const estiloEstado = color ?
                    `style="background:${color}1A;color:${color};border-color:${color}33;"` :
                    '';

                const paciente = escapeHtml(cita.paciente || 'Paciente sin nombre');
                const servicio = escapeHtml(cita.servicio || 'Sin servicio');
                const doctor = escapeHtml(cita.doctor || 'Sin doctor');
                const fecha = escapeHtml(formatearFecha(cita.fecha_cita));
                const motivo = cita.motivo ? ` · ${escapeHtml(cita.motivo)}` : '';

                return `
                <article class="appointment-card is-clickable" data-id-cita="${escapeHtml(cita.id_cita)}">
                    <span class="time-chip">
                        ${escapeHtml(hora.hora)}
                        <small>${escapeHtml(hora.periodo)}</small>
                    </span>

                    <div class="card-copy">
                        <h3>${paciente}</h3>
                        <p>${servicio} · ${doctor} · ${fecha}${motivo}</p>
                    </div>

                    <div class="card-meta">
                        <span class="status-pill ${estadoClass}" ${estiloEstado}>
                            ${escapeHtml(estado)}
                        </span>
                    </div>
                </article>
            `;
            }).join('');
        }

        async function cargarCitas() {
            lista.innerHTML = `
            <article class="appointment-card">
                <span class="time-chip">--:--<small></small></span>
                <div class="card-copy">
                    <h3>Cargando citas...</h3>
                    <p>Consultando información registrada.</p>
                </div>
                <div class="card-meta">
                    <span class="status-pill">Cargando</span>
                </div>
            </article>
        `;

            try {
                const url = new URL(API_URL, window.location.href);

                url.searchParams.set('inicio', fechaInicio.value || '');
                url.searchParams.set('fin', fechaFin.value || '');

                const response = await fetch(url.toString(), {
                    method: 'GET',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const texto = await response.text();

                let json;

                try {
                    json = JSON.parse(texto);
                } catch (e) {
                    console.error('Respuesta no JSON del API:', texto);
                    throw new Error('El API no devolvió JSON. Revisa CORS, sesión o ruta.');
                }

                if (!response.ok || !json.ok) {
                    console.error('Error del API:', json);
                    throw new Error(json.mensaje || json.error || 'No se pudo cargar la información.');
                }

                citasOriginales = Array.isArray(json.data) ? json.data : [];
                pintarCitas();

            } catch (error) {
                lista.innerHTML = `
                <article class="appointment-card empty-state-card">
                    <div class="card-copy">
                        <h3>No se pudieron cargar las citas</h3>
                        <p>Verifica la sesión, la conexión o el archivo api/citas/listar.php.</p>
                    </div>
                    <div class="card-meta">
                        <span class="status-pill is-danger">Error</span>
                    </div>
                </article>
            `;
            }
        }

        buscar.addEventListener('input', pintarCitas);
        btnActualizar.addEventListener('click', cargarCitas);
        fechaInicio.addEventListener('change', cargarCitas);
        fechaFin.addEventListener('change', cargarCitas);

        cargarCitas();
    })();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>