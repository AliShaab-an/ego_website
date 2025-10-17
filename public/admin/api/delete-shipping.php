<?php
require_once __DIR__ . "/../../../app/controllers/shippingController.php";
header('Content-Type: application/json');

try {
    $controller = new ShippingController();
    echo json_encode($controller->deleteShipping());
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}