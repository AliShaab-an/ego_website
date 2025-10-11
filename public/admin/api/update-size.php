<?php
require_once __DIR__ . "/../../../app/controllers/SizesController.php";
header('Content-Type: application/json');

try {
    $controller = new SizesController();
    echo json_encode($controller->updateSize());
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}