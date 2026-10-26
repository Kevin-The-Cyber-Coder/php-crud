<?php
declare(strict_types=1);

/**
 * Single entry point for shared setup: configuration, helpers, database and
 * model functions plus the session that flash messages and CSRF tokens use.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/contacts.php';

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

date_default_timezone_set('UTC');
