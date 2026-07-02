<?php
require_once __DIR__ . '/../config/push.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/../data/push_subscriptions.json';

if (!file_exists($file)) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'message' => 'No hay dispositivos suscritos todavía',
    ]);
    exit;
}

$subscriptions = json_decode((string) file_get_contents($file), true);

if (!is_array($subscriptions) || count($subscriptions) === 0) {
    http_response_code(404);
    echo json_encode([
        'ok' => false,
        'message' => 'No hay suscripciones guardadas',
    ]);
    exit;
}

$webPush = new WebPush([
    'VAPID' => [
        'subject' => PUSH_VAPID_SUBJECT,
        'publicKey' => PUSH_VAPID_PUBLIC_KEY,
        'privateKey' => PUSH_VAPID_PRIVATE_KEY,
    ],
]);

$payload = json_encode([
    'title' => 'Dental App',
    'body' => 'Prueba exitosa: las notificaciones push ya funcionan.',
    'url' => appUrl('index.php'),
    'icon' => appUrl('assets/img/icon-192.png'),
    'badge' => appUrl('assets/img/icon-192.png'),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$success = 0;
$failed = 0;
$errors = [];

foreach ($subscriptions as $id => $item) {
    try {
        $subscription = Subscription::create($item['subscription']);

        $report = $webPush->sendOneNotification($subscription, $payload);

        if ($report->isSuccess()) {
            $success++;
        } else {
            $failed++;

            $errors[] = [
                'endpoint' => $report->getEndpoint(),
                'reason' => $report->getReason(),
            ];

            if ($report->isSubscriptionExpired()) {
                unset($subscriptions[$id]);
            }
        }
    } catch (Throwable $e) {
        $failed++;

        $errors[] = [
            'message' => $e->getMessage(),
        ];
    }
}

file_put_contents(
    $file,
    json_encode($subscriptions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    LOCK_EX
);

echo json_encode([
    'ok' => $success > 0,
    'success' => $success,
    'failed' => $failed,
    'errors' => $errors,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);