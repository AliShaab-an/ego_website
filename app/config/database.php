<?php
    // Load environment variables
    $envFile = __DIR__ . '/../../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $_ENV[trim($key)] = trim($value);
            }
        }
    }

    $dbHost = $_ENV['DB_HOST'] ?? 'localhost';
    $dbUser = $_ENV['DB_USER'] ?? 'root';
    $dbPassword = $_ENV['DB_PASSWORD'] ?? '';
    $dbName = $_ENV['DB_NAME'] ?? 'ego';
    $dbPort = $_ENV['DB_PORT'] ?? '3306';

    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

    $pdo = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;