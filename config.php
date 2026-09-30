<?php
/**
 * api/config.php
 * =============================================
 * CONFIGURAÇÃO CENTRAL — edite apenas aqui!
 * =============================================
 *
 * Para trocar de gateway, mude apenas ACTIVE_GATEWAY:
 *   'bynet'    → usa Bynet  (padrão)
 *   'paguenex' → usa PagueNex
 */

define('ACTIVE_GATEWAY', 'bynet'); // ← TROQUE AQUI

// ============================================================
// BYNET
// ============================================================
define('BYNET_API_KEY',      '7effd77d-97fe-467c-8108-8f696387d2c8');  // x-api-key
define('BYNET_BASE_URL',     'https://api-gateway.techbynet.com');
define('BYNET_TX_ENDPOINT',  '/api/user/transactions');
define('BYNET_TX_INFO',      '/api/user/transactions/'); // + {id}

// ============================================================
// PAGUENEX
// ============================================================
define('PAGUENEX_USER',      'SUA_CHAVE_API_PAGUENEX');
define('PAGUENEX_PASS',      'SUA_CHAVE_SECRETA_PAGUENEX');
define('PAGUENEX_BASE_URL',  'https://api.paguenexbr.com');
define('PAGUENEX_TX_ENDPOINT', '/v1/payment-transaction/create');
define('PAGUENEX_TX_INFO',   '/v1/payment-transaction/info/'); // + {id}

// ============================================================
// GERAL
// ============================================================
define('POSTBACK_URL', 'https://sejaumentregadorml.site/consulta/api/webhook.php');
define('DB_PATH',      __DIR__ . '/../data/transactions.db');

// ============================================================
// FACEBOOK — API de Conversões
// ============================================================
define('FB_PIXEL_ID',     '967485039080220');
define('FB_ACCESS_TOKEN', 'EAAFPHGVwhaoBRE8QGRtkGR8lCgZBBkUHUH9MSmvMDLQ7deuQ5Tmuz6CanxGmPM9jqMcf3SLm5HyfQ6uPbuQUYSznMrhN8GEKS2wOMJUE05ZBf1iZBbKFSDOGwQVQHUKtqssTU82EgZCJqSC5TPnSOeCZC9fKROXyyKZCpBwWt5MjXCVTgGYDHgLRKWZCCnORQZDZD');
define('FB_API_VERSION',  'v19.0');