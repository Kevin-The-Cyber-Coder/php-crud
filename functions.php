<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/** Escape a value for HTML output. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Folder the app lives in, e.g. "/php-crud" (empty when in the web root). */
function base_path(): string
{
    static $base = null;

    if ($base === null) {
        $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $base = ($directory === '/' || $directory === '.') ? '' : rtrim($directory, '/');
    }

    return $base;
}

/** Absolute URL for a path inside the app. */
function url(string $path = ''): string
{
    return base_path() . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/**
 * Set a one-time message when called with arguments, read it when called
 * without arguments.
 */
function flash(?string $type = null, ?string $message = null): ?array
{
    if ($type !== null) {
        $_SESSION['flash'] = ['type' => $type, 'message' => (string) $message];
        return null;
    }

    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return is_array($flash) ? $flash : null;
}

/** Random token stored in the session, rendered in every form. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/** Abort the request when the submitted token does not match the session. */
function csrf_verify(): void
{
    $sent = $_POST['csrf_token'] ?? '';

    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('The form has expired. Please go back, reload the page and try again.');
    }
}

/** Reject anything that is not a POST request. */
function require_post(): void
{
    if (!is_post()) {
        redirect('index.php');
    }
}

/** Multibyte-safe length, with a plain fallback. */
function text_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

/** Turn a stored timestamp into something readable. */
function format_datetime(?string $value): string
{
    if ($value === null || $value === '') {
        return '—';
    }

    $timestamp = strtotime($value);

    return $timestamp === false ? $value : date('Y-m-d H:i', $timestamp);
}
