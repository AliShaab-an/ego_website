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
        try {
            $stmt = self::getConnection()->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . implode(", ", self::getConnection()->errorInfo()));
            }
            
            $result = $stmt->execute($params);
            if (!$result) {
                throw new Exception("Failed to execute statement: " . implode(", ", $stmt->errorInfo()));
            }
            
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . json_encode($params));
            throw $e;
        }
    }
}
