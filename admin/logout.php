<?php
declare(strict_types=1);

require_once __DIR__ . '/_auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
    http_response_code(400);
    exit('Invalid logout request. Return to the dashboard and try again.');
}

$_SESSION = [];
session_regenerate_id(true);
session_destroy();

header('Location: login.php');
exit;
