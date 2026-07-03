<?php
$pageTitle = 'Perfil';
$activePage = 'perfil';
$pageKicker = 'Cuenta y sesión';

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-title-card">
    <h1>Perfil</h1>
    <p>Administra tu cuenta, revisa tu empresa activa y cierra sesión de forma segura.</p>
</section>

<section class="profile-card" style="
    background:#fff;
    border:1px solid rgba(15,23,42,.08);
    border-radius:24px;
    padding:22px;
    box-shadow:0 16px 40px rgba(15,23,42,.06);
    margin-bottom:18px;
">
    <div style="display:flex;align-items:center;gap:16px;">
        <div style="
            width:58px;
            height:58px;
            border-radius:20px;
            display:grid;
            place-items:center;
            background:linear-gradient(135deg, var(--primario, #0ea5b7), #073b46);
            color:#fff;
            flex:0 0 auto;
        ">
            <?= appIcon('users') ?>
        </div>

        <div style="min-width:0;">
            <h2 id="perfilNombre" style="margin:0;font-size:20px;line-height:1.2;">Cargando perfil...</h2>
            <p id="perfilEmpresa" style="margin:6px 0 0;color:#64748b;font-size:14px;">Validando sesión activa.</p>
        </div>
    </div>

    <div style="
        display:grid;
        gap:10px;
        margin-top:20px;
    ">
        <div style="
            display:flex;
            justify-content:space-between;
            gap:12px;
            border-top:1px solid rgba(15,23,42,.08);
            padding-top:14px;
        ">
            <span style="color:#64748b;">Rol</span>
            <strong id="perfilRol">-</strong>
        </div>

        <div style="
            display:flex;
            justify-content:space-between;
            gap:12px;
            border-top:1px solid rgba(15,23,42,.08);
            padding-top:14px;
        ">
            <span style="color:#64748b;">Empresa</span>
            <strong id="perfilEmpresaDetalle" style="text-align:right;">-</strong>
        </div>

        <div style="
            display:flex;
            justify-content:space-between;
            gap:12px;
            border-top:1px solid rgba(15,23,42,.08);
            padding-top:14px;
        ">
            <span style="color:#64748b;">Estado</span>
            <strong style="color:#16a34a;">Sesión activa</strong>
        </div>
    </div>
</section>

<section class="section-head">
    <div>
        <h2>Sesión</h2>
        <p>Cierra sesión en este dispositivo.</p>
    </div>
</section>

<article class="notice-card" style="border-color:rgba(239,68,68,.18);">
    <strong>Cerrar sesión</strong>
    <p>Al salir, deberás volver a ingresar con tu usuario y contraseña para ver las citas y módulos protegidos.</p>

    <button type="button" class="btn btn-soft" id="btnCerrarSesion" style="
        width:100%;
        margin-top:14px;
        justify-content:center;
        color:#b91c1c;
        border-color:rgba(239,68,68,.24);
        background:rgba(239,68,68,.06);
    ">
        Cerrar sesión
    </button>

    <p id="logoutMensaje" style="margin:12px 0 0;color:#64748b;font-size:14px;"></p>
</article>

<script>
(function () {
    'use strict';

    const API_ME = 'https://app.ortodonciaclinica.pe/api/auth/me.php';
    const API_LOGOUT = 'https://app.ortodonciaclinica.pe/api/auth/logout.php';

    const perfilNombre = document.getElementById('perfilNombre');
    const perfilEmpresa = document.getElementById('perfilEmpresa');
    const perfilRol = document.getElementById('perfilRol');
    const perfilEmpresaDetalle = document.getElementById('perfilEmpresaDetalle');
    const btnCerrarSesion = document.getElementById('btnCerrarSesion');
    const logoutMensaje = document.getElementById('logoutMensaje');

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    async function cargarPerfil() {
        try {
            const response = await fetch(API_ME, {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const texto = await response.text();
            const json = JSON.parse(texto);

            if (!response.ok || !json.ok || !json.logueado) {
                window.location.replace('/auth/login.php');
                return;
            }

            const usuario = json.usuario || {};

            const nombre = usuario.nombre_usuario || 'Usuario';
            const empresa = usuario.empresa_nombre || 'Empresa';
            const rol = usuario.rol || '-';

            perfilNombre.innerHTML = escapeHtml(nombre);
            perfilEmpresa.innerHTML = escapeHtml(empresa);
            perfilRol.innerHTML = escapeHtml(rol);
            perfilEmpresaDetalle.innerHTML = escapeHtml(empresa);

        } catch (error) {
            console.error(error);
            window.location.replace('/auth/login.php');
        }
    }

    async function cerrarSesion() {
        btnCerrarSesion.disabled = true;
        logoutMensaje.textContent = 'Cerrando sesión...';

        try {
            const response = await fetch(API_LOGOUT, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });

            let json = {};

            try {
                json = await response.json();
            } catch (e) {
                json = {};
            }

            if (!response.ok || json.ok === false) {
                throw new Error(json.mensaje || 'No se pudo cerrar sesión.');
            }

            window.location.replace('/auth/login.php');

        } catch (error) {
            btnCerrarSesion.disabled = false;
            logoutMensaje.textContent = error.message;
        }
    }

    btnCerrarSesion.addEventListener('click', cerrarSesion);

    cargarPerfil();
})();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>