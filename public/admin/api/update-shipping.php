<?php
require_once __DIR__ . "/../../../app/controllers/ShippingController.php";
header('Content-Type: application/json');

try {
    $controller = new ShippingController();
    echo json_encode($controller->updateShipping());
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}