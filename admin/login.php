<?php
declare(strict_types=1);

require_once __DIR__ . '/_auth.php';
require_once __DIR__ . '/../includes/database.php';

if (!empty($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}

$errorMessage = '';
$setupComplete = isset($_GET['setup']) && $_GET['setup'] === 'complete';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hasValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = 'Your form session expired. Reload this page and try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        try {
            $pdo = getDatabaseConnection();
            $statement = $pdo->prepare(
                'SELECT id, username, password_hash FROM admin_users WHERE username = :username LIMIT 1'
            );
            $statement->execute(['username' => $username]);
            $admin = $statement->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_user_id'] = (int) $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: index.php');
                exit;
            }

            $errorMessage = 'The username or password was not recognized.';
        } catch (PDOException $exception) {
            error_log('Administrator sign-in failed: ' . $exception->getMessage());
            $errorMessage = 'The administrator account could not be checked. Confirm that MySQL is running.';
        } catch (RuntimeException $exception) {
            $errorMessage = $exception->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Dashboard Login - Sweet Dreams Bakery</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-page">
    <main class="admin-card admin-auth-card">
        <p class="eyebrow">Sweet Dreams Bakery</p>
        <h1>Menu dashboard</h1>
        <p>Sign in to manage products, prices, descriptions, and pictures.</p>
        <?php if ($setupComplete): ?>
            <p class="notice">Administrator account created. Sign in to continue.</p>
        <?php endif; ?>
        <?php if ($errorMessage !== ''): ?>
            <p class="notice notice-error"><?= h($errorMessage) ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= h(adminCsrfToken()) ?>">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" autocomplete="username" required>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button class="button" type="submit">Sign in</button>
        </form>
        <a class="text-link" href="../index.php">Return to the bakery</a>
    </main>
</body>
</html>
