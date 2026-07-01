(() => {
    const body = document.body;
    const preloader = document.getElementById('appPreloader');
    const baseUrl = (window.APP_BASE_URL || '').replace(/\/$/, '');

    body.classList.add('is-loading');

    const hidePreloader = () => {
        if (!preloader) return;
        preloader.classList.add('is-hidden');
        body.classList.remove('is-loading');
    };

    window.addEventListener('load', () => {
        window.setTimeout(hidePreloader, 420);
    });

    window.setTimeout(hidePreloader, 1500);

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(`${baseUrl}/service-worker.js`, {
                scope: `${baseUrl}/`
            }).catch(() => {
                // En local puede fallar si no usas HTTPS o localhost.
            });
        });
    }

    document.querySelectorAll('[data-demo-search]').forEach((input) => {
        input.addEventListener('input', () => {
            const term = input.value.trim().toLowerCase();
            const target = input.getAttribute('data-demo-search');
            document.querySelectorAll(`[data-demo-item="${target}"]`).forEach((item) => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });
})();
