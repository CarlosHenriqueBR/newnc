<?php
/**
 * api/status.php
 * Verifica status de pagamento via gateway ativa.
 * GET ?hash=TX_ID&step=encomenda
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/gateway.php';

$hash = trim($_GET['hash'] ?? '');
if (!$hash) { http_response_code(400); echo json_encode(['error' => 'hash obrigatório']); exit; }

$db        = getDb();
$hash_safe = $db->escapeString($hash);

// 1) Banco local primeiro (webhook pode ter atualizado)
$row = $db->querySingle(
    "SELECT status, amount FROM transactions WHERE transaction_hash='{$hash_safe}'", true
);
if ($row && $row['status'] === 'PAID') {
    echo json_encode(['data' => ['is_paid' => true, 'amount' => $row['amount']]]);
    exit;
}

// 2) Consulta gateway ativa
try {
    $result = consultarTransacao($hash);
} catch (Exception $e) {
    error_log('[status.php] ' . $e->getMessage());
    echo json_encode(['data' => ['is_paid' => false]]);
    exit;
}

// 3) Se pago, atualiza banco
if ($result['is_paid']) {
    $db->exec("UPDATE transactions SET status='PAID', paid_at=strftime('%s','now')
               WHERE transaction_hash='{$hash_safe}'");
}

echo json_encode([
    'data' => [
        'is_paid' => $result['is_paid'],
        'status'  => $result['status'],
        'amount'  => $row['amount'] ?? 0,
    ]
]);