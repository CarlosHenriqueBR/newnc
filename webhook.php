<?php
/**
 * api/webhook.php
 * Recebe notificações das gateways Bynet e PagueNex.
 * Atualiza banco e dispara Purchase na API de Conversões do Facebook.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$rawBody = file_get_contents('php://input');
$payload = json_decode($rawBody, true);

error_log('[webhook] gateway=' . ACTIVE_GATEWAY . ' | ' . $rawBody);

if (!$payload) { http_response_code(400); echo 'Invalid payload'; exit; }

// ============================================================
// Normaliza payload (Bynet e PagueNex têm formatos diferentes)
// ============================================================
$txId   = null;
$status = null;
$amount = 0;

if (ACTIVE_GATEWAY === 'bynet') {
    // Bynet envia: { "event": "transaction.paid", "data": { "id": "...", "status": "PAID", "amount": 1000 } }
    $txId   = $payload['data']['id']     ?? $payload['id']     ?? null;
    $status = $payload['data']['status'] ?? $payload['status'] ?? null;
    $amount = $payload['data']['amount'] ?? $payload['amount'] ?? 0;
} else {
    // PagueNex
    $txId   = $payload['data']['id']     ?? $payload['id']     ?? null;
    $status = $payload['data']['status'] ?? $payload['status'] ?? null;
    $amount = $payload['data']['amount'] ?? $payload['amount'] ?? 0;
}

if (!$txId || !$status) { http_response_code(422); echo 'Missing fields'; exit; }

$isPaid = in_array(strtoupper($status), ['PAID', 'APPROVED', 'CONFIRMED', 'COMPLETED', 'PAID_OUT'], true);

if ($isPaid) {
    $db        = getDb();
    $txId_safe = $db->escapeString($txId);

    $row = $db->querySingle(
        "SELECT * FROM transactions
         WHERE (transaction_id='{$txId_safe}' OR transaction_hash='{$txId_safe}') AND status!='PAID'",
        true
    );

    if ($row) {
        $db->exec("UPDATE transactions SET status='PAID', paid_at=strftime('%s','now')
                   WHERE (transaction_id='{$txId_safe}' OR transaction_hash='{$txId_safe}')");

        $tracking = [];
        $trackRow = $db->querySingle(
            "SELECT data FROM tracking WHERE transaction_id='{$txId_safe}'", true
        );
        if ($trackRow) $tracking = json_decode($trackRow['data'], true) ?? [];

        dispararFacebookPurchase($row, $amount ?: (int)$row['amount'], $tracking);

        error_log(sprintf('[webhook] Confirmado | gateway=%s | step=%s | cpf=%s | R$%.2f',
            ACTIVE_GATEWAY, $row['step'], $row['cpf'], ($amount ?: $row['amount']) / 100));
    }
}

http_response_code(200);
echo 'OK';

// ============================================================
// FACEBOOK CAPI
// ============================================================
function dispararFacebookPurchase(array $row, int $amount, array $tracking): void {
    $url     = sprintf('https://graph.facebook.com/%s/%s/events', FB_API_VERSION, FB_PIXEL_ID);
    $valor   = round($amount / 100, 2);
    $eventId = 'purchase_' . $row['transaction_id'] . '_' . time();

    $userData = [];
    if (!empty($row['email']))
        $userData['em'] = hash('sha256', strtolower(trim($row['email'])));
    if (!empty($row['telefone'])) {
        $tel = preg_replace('/\D/', '', $row['telefone']);
        if (strlen($tel) === 11) $tel = '55' . $tel;
        $userData['ph'] = hash('sha256', $tel);
    }
    if (!empty($row['cpf']))
        $userData['external_id'] = hash('sha256', preg_replace('/\D/', '', $row['cpf']));

    $eventData = [
        'event_name'       => 'Purchase',
        'event_time'       => time(),
        'event_id'         => $eventId,
        'action_source'    => 'website',
        'event_source_url' => 'https://sejaumentregadorml.site/consulta/confirmacao.php',
        'user_data'        => $userData,
        'custom_data'      => ['currency' => 'BRL', 'value' => $valor, 'content_name' => $row['step'] ?? ''],
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query(['data' => json_encode([$eventData]), 'access_token' => FB_ACCESS_TOKEN]),
        CURLOPT_TIMEOUT        => 10,
    ]);
    $response = curl_exec($ch);
    $err      = curl_error($ch);
    curl_close($ch);

    if ($err) { error_log('[webhook] FB CAPI curl error: ' . $err); return; }

    $r = json_decode($response, true);
    if (isset($r['events_received']))
        error_log(sprintf('[webhook] FB CAPI OK | events=%d | R$%.2f', $r['events_received'], $valor));
    else
        error_log('[webhook] FB CAPI error: ' . $response);
}