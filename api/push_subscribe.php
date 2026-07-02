<?php
require_once __DIR__ . '/../config/app.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'Método no permitido',
    ]);
    exit;
}

$raw = file_get_contents('php://input');
$subscription = json_decode($raw, true);

if (
    !is_array($subscription) ||
    empty($subscription['endpoint']) ||
    empty($subscription['keys']['p256dh']) ||
    empty($subscription['keys']['auth'])
) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'message' => 'Suscripción inválida',
    ]);
    exit;
}

$dataDir = __DIR__ . '/../data';

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$file = $dataDir . '/push_subscriptions.json';

$subscriptions = [];

if (file_exists($file)) {
    $current = json_decode((string) file_get_contents($file), true);

    if (is_array($current)) {
        $subscriptions = $current;
    }
}

$id = hash('sha256', $subscription['endpoint']);

$subscriptions[$id] = [
    'subscription' => $subscription,
    'created_at' => date('Y-m-d H:i:s'),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
];

file_put_contents(
    $file,
    json_encode($subscriptions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

echo json_encode([
    'ok' => true,
    'message' => 'Dispositivo suscrito correctamente',
]);