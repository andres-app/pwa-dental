<?php
// index.php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';

header('Location: ' . appUrl('pages/inicio.php'));
exit;