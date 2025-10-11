<?php
require_once __DIR__ . "/../../../app/controllers/ColorsController.php";
header('Content-Type: application/json');

try {
    $controller = new ColorsController();
    echo json_encode($controller->deleteColor());
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}