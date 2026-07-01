PWA Dental Premium - Maquetación base PHP

Estructura:
- config/app.php: configuración y helpers.
- includes/header.php: header reutilizable con soporte safe-area para notch de iPhone.
- includes/footer.php: footer reutilizable fijo tipo app.
- assets/css/app.css: diseño completo responsive y premium.
- assets/js/app.js: preloader y registro básico del service worker.
- service-worker.js: cache básico para PWA.
- manifest.json: configuración PWA.
- index.php y pages/: vistas de ejemplo.

Uso:
1. Sube la carpeta a tu hosting PHP.
2. Abre index.php.
3. Las vistas jalan header/footer con require_once.
4. Cambia APP_BASE_URL en config/app.php si lo alojas dentro de una subcarpeta.

Nota:
Esta entrega es solo maquetación visual escalable. No incluye base de datos ni lógica de login.
