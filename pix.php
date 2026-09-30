<?php
/**
 * api/pix.php
 * Cria cobrança Pix (checkout principal — 3 steps).
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

$cpf       = preg_replace('/\D/', '', $body['cpf']      ?? '');
$nome      = trim($body['nome']     ?? '');
$email     = trim($body['email']    ?? '');
$telefone  = preg_replace('/\D/', '', $body['telefone'] ?? '');
$amount    = (int) ($body['amount']  ?? 0);
$step      = $body['step']           ?? '';
$sessionId = $body['session_id']     ?? ('sess_' . bin2hex(random_bytes(8)));

if (strlen($cpf) !== 11) { jsonError('CPF inválido', 400); }
if ($amount < 100)        { jsonError('Valor inválido', 400); }
if (!$step)               { jsonError('Step obrigatório', 400); }

// Já pagou esse step?
$db = getDb();
$cpf_safe  = $db->escapeString($cpf);
$step_safe = $db->escapeString($step);
$existing  = $db->querySingle(
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
        'cpf'        => $cpf,
        'nome'       => $nome,
        'email'      => $email,
        'telefone'   => $telefone,
        'amount'     => $amount,
        'step'       => $step,
        'session_id' => $sessionId,
        'descricao'  => getStepTitle($step),
    ]);
} catch (Exception $e) {
    error_log('[pix.php] ' . $e->getMessage());
    jsonError('Erro ao gerar PIX: ' . $e->getMessage(), 502);
}

// Salva no banco
$txId      = $db->escapeString($tx['transaction_id']);
$expiresTs = strtotime($tx['expires_at']);
$pixCode   = $db->escapeString($tx['pix_code']);
$receiver  = $db->escapeString($tx['receiver']);
$sid       = $db->escapeString($sessionId);

$db->exec("INSERT OR REPLACE INTO transactions
    (transaction_id, transaction_hash, step, cpf, nome, email, telefone, amount, pix_code, pix_qrcode, receiver, status, expires_at, session_id, created_at)
    VALUES ('{$txId}','{$txId}','{$step_safe}','{$cpf_safe}',
            '{$db->escapeString($nome)}','{$db->escapeString($email)}','{$db->escapeString($telefone)}',
            {$amount},'{$pixCode}','','{$receiver}','PENDING',{$expiresTs},'{$sid}',strftime('%s','now'))");

// Tracking
$trackingFields = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign'];
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
        'expires_at'       => $tx['expires_at'],
        'receiver'         => ['name' => $tx['receiver']],
    ],
]);

function getStepTitle(string $step): string {
    return [
        'encomenda'  => 'Hábitos de uma vida saudável - Melhore sua vida',
        'notafiscal' => 'Nota Fiscal Eletrônica (TENF)',
        'frete'      => 'Taxa de Desembaraço Aduaneiro (TDA)',
    ][$step] ?? 'Taxa';
}

function jsonError(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}