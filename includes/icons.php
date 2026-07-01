<?php
if (!function_exists('appIcon')) {
    function appIcon(string $name): string
    {
        $base = 'width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';

        $icons = [
            'home' => '<svg '.$base.'><path d="M3 10.7 12 3l9 7.7"/><path d="M5 10v10h14V10"/><path d="M9.5 20v-6h5v6"/></svg>',
            'users' => '<svg '.$base.'><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'calendar' => '<svg '.$base.'><path d="M8 2v4"/><path d="M16 2v4"/><rect x="3.5" y="5" width="17" height="16" rx="4"/><path d="M3.5 10h17"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/></svg>',
            'clipboard' => '<svg '.$base.'><rect x="5" y="4" width="14" height="17" rx="3"/><path d="M9 4.5A3 3 0 0 1 12 2a3 3 0 0 1 3 2.5"/><path d="M9 9h6"/><path d="M9 13h6"/><path d="M9 17h4"/></svg>',
            'grid' => '<svg '.$base.'><rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/></svg>',
            'bell' => '<svg '.$base.'><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
            'tooth' => '<svg '.$base.'><path d="M8.7 3.4c1.6-.6 2.4.4 3.3.4s1.7-1 3.3-.4c2.5.9 3.5 4 2.4 6.9-.8 2.2-1.3 5.5-2.2 7.7-.5 1.2-1.2 2-2.1 2-.8 0-1.1-.9-1.4-2.2-.3-1.3-.5-2.3-1-2.3s-.7 1-1 2.3c-.3 1.3-.6 2.2-1.4 2.2-.9 0-1.6-.8-2.1-2-.9-2.2-1.4-5.5-2.2-7.7-1.1-2.9-.1-6 2.4-6.9Z"/></svg>',
            'plus' => '<svg '.$base.'><path d="M12 5v14"/><path d="M5 12h14"/></svg>',
            'search' => '<svg '.$base.'><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>',
            'chart' => '<svg '.$base.'><path d="M4 19V5"/><path d="M4 19h16"/><rect x="7" y="11" width="3" height="5" rx="1"/><rect x="12" y="7" width="3" height="9" rx="1"/><rect x="17" y="9" width="3" height="7" rx="1"/></svg>',
            'clock' => '<svg '.$base.'><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
            'heart' => '<svg '.$base.'><path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6L12 21l8.8-8.8a5.4 5.4 0 0 0 0-7.6Z"/></svg>',
            'menu' => '<svg '.$base.'><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>',
            'arrow' => '<svg '.$base.'><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>',
            'shield' => '<svg '.$base.'><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-5"/></svg>',
        ];

        return $icons[$name] ?? $icons['grid'];
    }
}
