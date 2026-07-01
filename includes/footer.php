<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/icons.php';

$activePage = $activePage ?? 'inicio';
$navItems = appNavItems();
?>
    </main>

    <footer class="app-footer" role="navigation" aria-label="Navegación principal">
        <nav class="bottom-nav">
            <?php foreach ($navItems as $key => $item): ?>
                <a class="bottom-nav__item <?= $activePage === $key ? 'is-active' : '' ?>" href="<?= e($item['url']) ?>">
                    <span class="bottom-nav__icon"><?= appIcon($item['icon']) ?></span>
                    <span><?= e($item['label']) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </footer>
</div>

<script>window.APP_BASE_URL = <?= json_encode(appBaseUrl(), JSON_UNESCAPED_SLASHES) ?>;</script>
<script src="<?= e(assetUrl('assets/js/app.js')) ?>" defer></script>
</body>
</html>
