<?php
/**
 * api/save-tracking.php
 * Salva parâmetros de rastreamento (UTM, gclid, etc.)
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

define('DB_PATH', __DIR__ . '/../data/transactions.db');
require_once __DIR__ . '/db.php';

$body = json_decode(file_get_contents('php://input'), true);

$sessionId = $body['session_id'] ?? '';
$trackingFields = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_content','utm_term'];

$tracking = [];
foreach ($trackingFields as $k) {
    if (!empty($body[$k])) $tracking[$k] = $body[$k];
}

if ($sessionId && $tracking) {
    $db = getDb();
    $sid = $db->escapeString($sessionId);
    $data = $db->escapeString(json_encode($tracking));
    $db->exec("INSERT OR REPLACE INTO tracking (transaction_id, data) VALUES ('{$sid}', '{$data}')");
}

echo json_encode(['success' => true]);