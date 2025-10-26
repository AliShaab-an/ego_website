<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../app/controllers/shippingController.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        exit;
    }

    $controller = new shippingController();
    echo json_encode($controller->toggleStatus());

} catch (Exception $e) {
    error_log("Toggle shipping status error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while updating the region status']);
}
?>