<?php
class DB
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $dbFile = CONFIG . 'database.php';
        if (!file_exists($dbFile)) {
            throw new Exception("Missing database config file: {$dbFile}");
        }

        $pdo = require $dbFile;

        if (!($pdo instanceof PDO)) {
            throw new Exception("database.php must return a PDO instance");
        }

        self::$pdo = $pdo;
        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
