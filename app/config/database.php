<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ego", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "DB Error: " . $e->getMessage();
}
