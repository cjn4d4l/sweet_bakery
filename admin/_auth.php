<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';

// Keep the administrator session separate from other apps on the same host.
session_name('sweet_bakery_admin');
session_set_cookie_params([
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Strict',
]);
session_start();

function adminCsrfToken(): string
{
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf_token'];
}

function hasValidCsrfToken($submittedToken): bool
{
    return is_string($submittedToken)
        && isset($_SESSION['admin_csrf_token'])
        && hash_equals($_SESSION['admin_csrf_token'], $submittedToken);
}

function requireAdmin(): void
{
    if (empty($_SESSION['admin_user_id'])) {
        header('Location: login.php');
        exit;
    }
}
