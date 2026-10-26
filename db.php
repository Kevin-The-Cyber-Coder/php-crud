<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Shared PDO connection (created once per request).
 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    if (DB_DRIVER === 'sqlite') {
        $directory = dirname(SQLITE_FILE);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $pdo = new PDO('sqlite:' . SQLITE_FILE, null, null, $options);
        create_sqlite_schema($pdo);

        return $pdo;
    }

    if (DB_DRIVER !== 'mysql') {
        throw new RuntimeException('Unsupported DB_DRIVER: ' . DB_DRIVER);
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        DB_HOST,
        DB_PORT,
        DB_NAME
    );

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    return $pdo;
}

/**
 * Create the contacts table in SQLite on first run.
 */
function create_sqlite_schema(PDO $pdo): void
{
    $exists = $pdo->query(
        "SELECT name FROM sqlite_master WHERE type = 'table' AND name = 'contacts'"
    )->fetchColumn();

    if ($exists !== false) {
        return;
    }

    $pdo->exec(
        'CREATE TABLE contacts (
            id         INTEGER PRIMARY KEY AUTOINCREMENT,
            name       TEXT    NOT NULL,
            email      TEXT    NOT NULL,
            phone      TEXT    NOT NULL DEFAULT \'\',
            notes      TEXT    NULL,
            created_at TEXT    NOT NULL DEFAULT CURRENT_TIMESTAMP
        )'
    );
}
