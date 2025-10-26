<?php
require_once __DIR__ . "/../../../app/controllers/admin/ProductAdminController.php";
header('Content-Type: application/json');

try {
    $controller = new ProductAdminController();
    echo json_encode($controller->deleteProduct());
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}