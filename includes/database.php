<?php
declare(strict_types=1);

/**
 * Create one reusable PDO connection to the MySQL database.
 *
 * XAMPP defaults are used for local learning. Set DB_HOST, DB_NAME, DB_USER,
 * and DB_PASS in your PHP/Apache environment when using different credentials.
 */
function getDatabaseConnection(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1:3308';
    $database = getenv('DB_NAME') ?: 'sweet_bakery';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';
    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

    try {
        $connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        error_log('MySQL connection failed: ' . $exception->getMessage());
        throw new RuntimeException(
            'Could not connect to MySQL. Check that MySQL is running and the database credentials are correct.',
            0,
            $exception
        );
    }

    return $connection;
}
