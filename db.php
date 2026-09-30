<?php
/**
 * api/db.php
 * Inicializa o banco SQLite e retorna a conexão.
 */

function getDb(): SQLite3 {
    $dir = dirname(DB_PATH);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $db = new SQLite3(DB_PATH);
    $db->enableExceptions(true);
    $db->exec('PRAGMA journal_mode=WAL');

    // Tabela de transações
    $db->exec("
        CREATE TABLE IF NOT EXISTS transactions (
            id               INTEGER PRIMARY KEY AUTOINCREMENT,
            transaction_id   TEXT UNIQUE NOT NULL,
            transaction_hash TEXT,
            step             TEXT NOT NULL,
            cpf              TEXT NOT NULL,
            nome             TEXT,
            email            TEXT,
            telefone         TEXT,
            amount           INTEGER NOT NULL,
            pix_code         TEXT,
            pix_qrcode       TEXT,
            receiver         TEXT,
            status           TEXT DEFAULT 'PENDING',
            expires_at       INTEGER,
            session_id       TEXT,
            created_at       INTEGER DEFAULT (strftime('%s','now')),
            paid_at          INTEGER
        )
    ");

    // Índices para consultas rápidas
    $db->exec("CREATE INDEX IF NOT EXISTS idx_tx_hash  ON transactions(transaction_hash)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_tx_cpf   ON transactions(cpf, step)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_tx_status ON transactions(status)");

    // Tabela de tracking
    $db->exec("
        CREATE TABLE IF NOT EXISTS tracking (
            id             INTEGER PRIMARY KEY AUTOINCREMENT,
            transaction_id TEXT UNIQUE,
            data           TEXT,
            created_at     INTEGER DEFAULT (strftime('%s','now'))
        )
    ");

    return $db;
}