<?php
/**
 * api/lote-pix.php
 * Cria cobrança Pix para os lotes (upsell).
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/gateway.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { jsonError('Método não permitido', 405); }

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) { jsonError('Payload inválido', 400); }

// Valores fixos por lote — nunca usa o valor do cliente
$lotesPermitidos = [1 => 3700, 2 => 4700, 3 => 6700];

$loteId   = (int) ($body['lote_id'] ?? 0);
$cpf      = preg_replace('/\D/', '', $body['cpf']      ?? '');
$nome     = trim($body['nome']     ?? '');
$email    = trim($body['email']    ?? '');
$telefone = preg_replace('/\D/', '', $body['telefone'] ?? '');
$loteName = trim($body['lote_name'] ?? 'Lote Correios');

if (!isset($lotesPermitidos[$loteId])) { jsonError('Lote inválido', 400); }
if (strlen($cpf) !== 11)               { jsonError('CPF inválido', 400); }

$amount    = $lotesPermitidos[$loteId];
$step_safe = 'lote_' . $loteId;

// Já pagou esse lote?
$db = getDb();
$cpf_safe = $db->escapeString($cpf);
$existing = $db->querySingle(
    "SELECT status FROM transactions WHERE cpf='{$cpf_safe}' AND step='{$step_safe}' AND status='PAID'", true
);
if ($existing) {
    http_response_code(409);
    echo json_encode(['success' => false, 'error' => 'already_paid']);
    exit;
}

// Chama gateway ativa
try {
    $tx = criarTransacao([
        'cpf'       => $cpf,
        'nome'      => $nome,
        'email'     => $email,
        'telefone'  => $telefone,
        'amount'    => $amount,
        'step'      => $step_safe,
        'descricao' => $loteName,
    ]);
} catch (Exception $e) {
    error_log('[lote-pix.php] ' . $e->getMessage());
    jsonError('Erro ao gerar PIX: ' . $e->getMessage(), 502);
}

// Salva no banco
$txId      = $db->escapeString($tx['transaction_id']);
$expiresTs = strtotime($tx['expires_at']);
$pixCode   = $db->escapeString($tx['pix_code']);
$receiver  = $db->escapeString($tx['receiver']);
$sid       = 'lote_sess_' . substr($cpf, -4);

$db->exec("INSERT OR REPLACE INTO transactions
    (transaction_id, transaction_hash, step, cpf, nome, email, telefone, amount, pix_code, pix_qrcode, receiver, status, expires_at, session_id, created_at)
    VALUES ('{$txId}','{$txId}','{$step_safe}','{$cpf_safe}',
            '{$db->escapeString($nome)}','{$db->escapeString($email)}','{$db->escapeString($telefone)}',
            {$amount},'{$pixCode}','','{$receiver}','PENDING',{$expiresTs},'{$sid}',strftime('%s','now'))");

// Tracking
$trackingFields = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','src'];
$tracking = [];
foreach ($trackingFields as $k) { if (!empty($body[$k])) $tracking[$k] = $body[$k]; }
if ($tracking) {
    $td = $db->escapeString(json_encode($tracking));
    $db->exec("INSERT OR REPLACE INTO tracking (transaction_id, data) VALUES ('{$txId}', '{$td}')");
}

echo json_encode([
    'success' => true,
    'data'    => [
        'transaction_hash' => $tx['transaction_id'],
        'pix_code'         => $tx['pix_code'],
        'pix_qrcode'       => null,
        'amount'           => $tx['amount'],
        'amount_formatted' => 'R$ ' . number_format($tx['amount'] / 100, 2, ',', '.'),
        'expires_at'       => $tx['expires_at'],
        'receiver'         => ['name' => $tx['receiver']],
    ],
]);

function jsonError(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}