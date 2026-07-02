<?php
require_once __DIR__ . '/../config/push.php';

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'publicKey' => PUSH_VAPID_PUBLIC_KEY,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);