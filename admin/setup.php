<?php
declare(strict_types=1);

require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../includes/database.php';

$errorMessage = '';
$isLocalRequest = isset($_SERVER['REMOTE_ADDR'])
    && in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true);

try {
    $pdo = getDatabaseConnection();
    $adminCount = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
} catch (PDOException $exception) {
    error_log('Unable to check administrator setup: ' . $exception->getMessage());
    $adminCount = 0;
    $errorMessage = 'The database could not be checked. Confirm that you imported database.sql and started MySQL.';
} catch (RuntimeException $exception) {
    $adminCount = 0;
    $errorMessage = $exception->getMessage();
}

if ($isLocalRequest && $adminCount === 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && $errorMessage === '') {
    if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your form session expired. Reload this page and try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

        if (!preg_match('/^[A-Za-z0-9_.-]{3,60}$/', $username)) {
            $errorMessage = 'Use 3 to 60 letters, numbers, dots, underscores, or hyphens for the username.';
        } elseif (strlen($password) < 12) {
            $errorMessage = 'Choose a password with at least 12 characters.';
        } elseif ($password !== $passwordConfirmation) {
            $errorMessage = 'The password confirmation does not match.';
        } else {
            try {
                // password_hash stores a one-way hash; the original password is never saved.
                $statement = $pdo->prepare(
                    'INSERT INTO admin_users (username, password_hash) VALUES (:username, :password_hash)'
                );
                $statement->execute([
                    'username' => $username,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);
                header('Location: login.php?setup=complete');
                exit;
            } catch (PDOException $exception) {
                error_log('Unable to create first administrator: ' . $exception->getMessage());
                $errorMessage = 'The administrator account could not be created. Reload the page and check the username.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Administrator - Sweet Dreams Bakery</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-page">
    <main class="admin-card admin-auth-card">
        <p class="eyebrow">Sweet Dreams Bakery</p>
        <h1>Set up your menu dashboard</h1>
        <?php if (!$isLocalRequest): ?>
            <p class="notice notice-error">For safety, first-time setup is only available from this computer.</p>
        <?php elseif ($adminCount > 0): ?>
            <p class="notice">An administrator account already exists. The setup page is now locked.</p>
            <a class="button" href="login.php">Go to administrator login</a>
        <?php else: ?>
            <p>Create the first administrator account. Use a password with at least 12 characters.</p>
            <?php if ($errorMessage !== ''): ?>
                <p class="notice notice-error"><?= h($errorMessage) ?></p>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" minlength="3" maxlength="60" autocomplete="username" required>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" minlength="12" autocomplete="new-password" required>
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" minlength="12" autocomplete="new-password" required>
                <button class="button" type="submit">Create administrator</button>
            </form>
        <?php endif; ?>
        <a class="text-link" href="../index.php">Return to the bakery</a>
    </main>
</body>
</html>
