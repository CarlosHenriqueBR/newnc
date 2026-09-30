<?php
/**
 * api/gateway.php
 * Camada de abstração entre os arquivos PHP e as gateways.
 * Chame criarTransacao() e consultarTransacao() — ele usa a gateway ativa.
 */

require_once __DIR__ . '/config.php';

// ============================================================
// INTERFACE PÚBLICA
// ============================================================

/**
 * Cria uma cobrança Pix na gateway ativa.
 * Retorna array normalizado:
 * [
 *   'transaction_id'   => string,
 *   'pix_code'         => string,   (copia e cola)
 *   'expires_at'       => string,   (ISO 8601)
 *   'receiver'         => string,
 *   'amount'           => int,      (centavos)
 * ]
 */
function criarTransacao(array $params): array {
    if (ACTIVE_GATEWAY === 'bynet') {
        return _bynetCriar($params);
    }
    return _paguenexCriar($params);
}

/**
 * Consulta status de uma transação na gateway ativa.
 * Retorna: ['is_paid' => bool, 'status' => string]
 */
function consultarTransacao(string $txId): array {
    if (ACTIVE_GATEWAY === 'bynet') {
        return _bynetConsultar($txId);
    }
    return _paguenexConsultar($txId);
}

// ============================================================
// BYNET
// ============================================================

function _bynetCriar(array $p): array {
    $payload = [
        'amount'        => (int) $p['amount'],
        'paymentMethod' => 'PIX',
        'customer'      => [
            'name'  => $p['nome']  ?: 'Cliente',
            'email' => $p['email'] ?: 'cliente@email.com',
            'phone' => _fone($p['telefone']),
            'document' => [
                'type'   => 'CPF',
                'number' => $p['cpf'],
            ],
        ],
        'items' => [
            [
                'title'      => $p['descricao'] ?? 'Pagamento',
                'unitPrice'  => (int) $p['amount'],
                'quantity'   => 1,
                'tangible'   => false,
            ]
        ],
        'pix'         => ['expiresInDays' => 1],
        'postbackUrl' => POSTBACK_URL,
        'metadata'    => json_encode([
            'step'       => $p['step'] ?? '',
            'session_id' => $p['session_id'] ?? '',
            'cpf'        => $p['cpf'] ?? '',
        ]),
    ];

    $response = _httpPost(
        BYNET_BASE_URL . BYNET_TX_ENDPOINT,
        $payload,
        ['x-api-key: ' . BYNET_API_KEY, 'User-Agent: AtivoB2B/1.0']
    );

    if (empty($response['data']['id'])) {
        $msg = $response['message'] ?? $response['error'] ?? 'Erro desconhecido Bynet';
        throw new Exception('Bynet: ' . $msg);
    }

    $data = $response['data'];
    return [
        'transaction_id' => $data['id'],
        'pix_code'       => $data['pix']['qrcode'] ?? $data['qrCode'] ?? '',
        'expires_at'     => $data['pix']['expirationDate'] ?? date('c', strtotime('+15 minutes')),
        'receiver'       => $data['customer']['name'] ?? '',
        'amount'         => (int) ($data['amount'] ?? $p['amount']),
    ];
}

function _bynetConsultar(string $txId): array {
    $response = _httpGet(
        BYNET_BASE_URL . BYNET_TX_INFO . $txId,
        ['x-api-key: ' . BYNET_API_KEY, 'User-Agent: AtivoB2B/1.0']
    );

    $status = strtoupper($response['data']['status'] ?? '');
    $isPaid = in_array($status, ['PAID', 'APPROVED', 'CONFIRMED', 'COMPLETED', 'PAID_OUT'], true);

    return ['is_paid' => $isPaid, 'status' => $status];
}

// ============================================================
// PAGUENEX
// ============================================================

function _paguenexCriar(array $p): array {
    $credenciais = base64_encode(PAGUENEX_USER . ':' . PAGUENEX_PASS);

    $payload = [
        'payment_method' => 'pix',
        'customer'       => [
            'document' => ['type' => 'cpf', 'number' => $p['cpf']],
            'name'     => $p['nome']  ?: 'Cliente',
            'email'    => $p['email'] ?: 'cliente@email.com',
            'phone'    => _fone($p['telefone']),
        ],
        'items' => [
            [
                'title'      => $p['descricao'] ?? 'Pagamento',
                'unit_price' => (int) $p['amount'],
                'quantity'   => 1,
            ]
        ],
        'amount'       => (int) $p['amount'],
        'postback_url' => POSTBACK_URL,
        'metadata'     => ['provider_name' => 'API Pix', 'step' => $p['step'] ?? ''],
    ];

    $response = _httpPost(
        PAGUENEX_BASE_URL . PAGUENEX_TX_ENDPOINT,
        $payload,
        ['authorization: Basic ' . $credenciais]
    );

    if (!($response['success'] ?? false)) {
        $msgs = $response['error_messages'] ?? [$response['error'] ?? 'Erro PagueNex'];
        throw new Exception('PagueNex: ' . implode(', ', $msgs));
    }

    $data = $response['data'];
    return [
        'transaction_id' => $data['id'],
        'pix_code'       => $data['pix']['qr_code'] ?? '',
        'expires_at'     => $data['pix']['expiration_date'] ?? date('c', strtotime('+15 minutes')),
        'receiver'       => $data['receiver']['name'] ?? '',
        'amount'         => (int) ($data['amount'] ?? $p['amount']),
    ];
}

function _paguenexConsultar(string $txId): array {
    $credenciais = base64_encode(PAGUENEX_USER . ':' . PAGUENEX_PASS);

    $response = _httpGet(
        PAGUENEX_BASE_URL . PAGUENEX_TX_INFO . $txId,
        ['authorization: Basic ' . $credenciais]
    );

    $status = strtoupper($response['data']['status'] ?? $response['status'] ?? '');
    $isPaid = in_array($status, ['PAID', 'APPROVED', 'CONFIRMED', 'COMPLETED'], true);

    return ['is_paid' => $isPaid, 'status' => $status];
}

// ============================================================
// HELPERS HTTP
// ============================================================

function _httpPost(string $url, array $data, array $extraHeaders = []): array {
    $headers = array_merge(['Content-Type: application/json'], $extraHeaders);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) throw new Exception('cURL error: ' . $curlError);

    $decoded = json_decode($response, true);
    if ($decoded === null) throw new Exception('Resposta inválida da gateway: ' . $response);

    return $decoded;
}

function _httpGet(string $url, array $extraHeaders = []): array {
    $headers = array_merge(['Content-Type: application/json'], $extraHeaders);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response  = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) throw new Exception('cURL error: ' . $curlError);

    $decoded = json_decode($response, true);
    if ($decoded === null) throw new Exception('Resposta inválida da gateway: ' . $response);

    return $decoded;
}

function _fone(string $tel): string {
    $digits = preg_replace('/\D/', '', $tel);
    if (!$digits) return '5511999999999';
    if (strlen($digits) <= 11) $digits = '55' . $digits;
    return $digits;
}