<?php
// includes/footer.php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/icons.php';

$activePage = $activePage ?? 'inicio';
$navItems = appNavItems();

if (!function_exists('premiumBottomIcon')) {
    function premiumBottomIcon(string $name): string
    {
        $base = 'width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.95" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';

        return match ($name) {
            'home' => '<svg ' . $base . '><path d="M3 10.8 12 3l9 7.8"/><path d="M5 10v10h14V10"/><path d="M9.5 20v-6h5v6"/></svg>',
            'users' => '<svg ' . $base . '><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'plus' => '<svg ' . $base . '><path d="M12 5v14"/><path d="M5 12h14"/></svg>',
            'calendar' => '<svg ' . $base . '><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="3"/><path d="M3 10h18"/></svg>',
            'user', 'profile' => '<svg ' . $base . '><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>',
            default => appIcon($name),
        };
    }
}
?>
</main>

<footer class="app-footer premium-footer" role="navigation" aria-label="Navegación principal">
    <nav class="premium-bottom-nav">
        <?php foreach ($navItems as $key => $item): ?>
            <?php
            $isCenter = !empty($item['center']);
            $isActive = $activePage === $key;

            if ($key === 'perfil' && $activePage === 'mas') {
                $isActive = true;
            }

            if ($key === 'citas' && $activePage === 'agenda') {
                $isActive = true;
            }
            ?>

            <a
                class="premium-bottom-nav__item <?= $isCenter ? 'is-center' : '' ?> <?= $isActive ? 'is-active' : '' ?>"
                href="<?= e($item['url']) ?>"
                <?= $isActive ? 'aria-current="page"' : '' ?>>
                <span class="premium-bottom-nav__icon" aria-hidden="true">
                    <?= premiumBottomIcon($item['icon']) ?>
                </span>

                <span class="premium-bottom-nav__label">
                    <?= e($item['label']) ?>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>
</footer>
</div>

<style>
    .app-main {
        padding-bottom: calc(102px + env(safe-area-inset-bottom));
    }

    .premium-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        padding: 0 16px calc(8px + env(safe-area-inset-bottom));
        background: transparent;
        pointer-events: none;
    }

    .premium-bottom-nav {
        width: min(680px, 100%);
        min-height: 78px;
        margin: 0 auto;
        padding: 8px 10px;
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        align-items: center;
        gap: 4px;
        border-radius: 28px;
        background: rgba(255, 255, 255, .94);
        border: 1px solid rgba(15, 23, 42, .07);
        box-shadow:
            0 18px 44px rgba(15, 23, 42, .15),
            inset 0 1px 0 rgba(255, 255, 255, .86);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        pointer-events: auto;
    }

    .premium-bottom-nav__item {
        position: relative;
        min-width: 0;
        min-height: 60px;
        border-radius: 20px;
        display: grid;
        place-items: center;
        align-content: center;
        gap: 4px;
        color: #667085;
        text-decoration: none;
        font-size: 11px;
        font-weight: 850;
        letter-spacing: -.02em;
        -webkit-tap-highlight-color: transparent;
        transition: transform .12s ease, color .12s ease, background .12s ease;
    }

    .premium-bottom-nav__icon {
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
    }

    .premium-bottom-nav__icon svg {
        width: 23px;
        height: 23px;
    }

    .premium-bottom-nav__label {
        display: block;
        white-space: nowrap;
        line-height: 1;
    }

    .premium-bottom-nav__item.is-active:not(.is-center) {
        color: #087486;
        background: rgba(255, 255, 255, .96);
        box-shadow:
            0 12px 26px rgba(14, 165, 183, .11),
            inset 0 0 0 1px rgba(14, 165, 183, .08);
    }

    .premium-bottom-nav__item.is-center {
        color: #07102c;
        gap: 5px;
        font-weight: 950;
    }

    .premium-bottom-nav__item.is-center .premium-bottom-nav__icon {
        width: 56px;
        height: 56px;
        margin-top: -30px;
        border-radius: 50%;
        color: #fff;
        background: linear-gradient(135deg, #15c4cc, #087486);
        box-shadow:
            0 16px 30px rgba(14, 165, 183, .32),
            inset 0 1px 0 rgba(255, 255, 255, .28);
    }

    .premium-bottom-nav__item.is-center .premium-bottom-nav__icon svg {
        width: 29px;
        height: 29px;
        stroke-width: 2.2;
    }

    .premium-bottom-nav__item.is-center .premium-bottom-nav__label {
        margin-top: -3px;
        color: #07102c;
        font-size: 11px;
        font-weight: 950;
    }

    .premium-bottom-nav__item:active {
        transform: scale(.97);
    }

    .premium-bottom-nav__item.is-center:active .premium-bottom-nav__icon {
        transform: scale(.96);
    }

    @media (max-width: 430px) {
        .app-main {
            padding-bottom: calc(96px + env(safe-area-inset-bottom));
        }

        .premium-footer {
            padding: 0 14px calc(8px + env(safe-area-inset-bottom));
        }

        .premium-bottom-nav {
            min-height: 74px;
            padding: 8px 9px;
            border-radius: 26px;
        }

        .premium-bottom-nav__item {
            min-height: 58px;
            border-radius: 19px;
            font-size: 11px;
        }

        .premium-bottom-nav__icon {
            width: 29px;
            height: 29px;
        }

        .premium-bottom-nav__icon svg {
            width: 22px;
            height: 22px;
        }

        .premium-bottom-nav__item.is-center .premium-bottom-nav__icon {
            width: 54px;
            height: 54px;
            margin-top: -29px;
        }

        .premium-bottom-nav__item.is-center .premium-bottom-nav__icon svg {
            width: 28px;
            height: 28px;
        }
    }

    @media (max-width: 390px) {
        .premium-footer {
            padding-left: 10px;
            padding-right: 10px;
        }

        .premium-bottom-nav {
            min-height: 70px;
            padding: 7px;
            border-radius: 24px;
            gap: 3px;
        }

        .premium-bottom-nav__item {
            min-height: 54px;
            border-radius: 17px;
            font-size: 10px;
        }

        .premium-bottom-nav__icon {
            width: 27px;
            height: 27px;
        }

        .premium-bottom-nav__icon svg {
            width: 21px;
            height: 21px;
        }

        .premium-bottom-nav__item.is-center .premium-bottom-nav__icon {
            width: 52px;
            height: 52px;
            margin-top: -28px;
        }

        .premium-bottom-nav__item.is-center .premium-bottom-nav__icon svg {
            width: 27px;
            height: 27px;
        }

        .premium-bottom-nav__item.is-center .premium-bottom-nav__label {
            font-size: 10px;
        }
    }

    @media (max-width: 350px) {
        .premium-footer {
            padding-left: 8px;
            padding-right: 8px;
        }

        .premium-bottom-nav__label {
            font-size: 9px;
        }

        .premium-bottom-nav__item.is-center .premium-bottom-nav__icon {
            width: 48px;
            height: 48px;
            margin-top: -26px;
        }
    }
</style>

<script>
    window.APP_BASE_URL = <?= json_encode(appUrl(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
</script>

<script src="<?= e(assetUrl('assets/js/app.js')) ?>" defer></script>
<script src="<?= e(assetUrl('assets/js/push.js')) ?>" defer></script>

<script>
    (function() {
        'use strict';

        /*
        |--------------------------------------------------------------------------
        | Service Worker
        |--------------------------------------------------------------------------
        | Se registra una sola vez. No usamos preloader en cada vista para evitar lag.
        */
        if ('serviceWorker' in navigator && !window.__dentalSwRegistered) {
            window.__dentalSwRegistered = true;

            navigator.serviceWorker.register('/service-worker.js?v=<?= e(APP_SW_VERSION) ?>', {
                scope: '/'
            });
        }
    })();
</script>
</body>

</html>