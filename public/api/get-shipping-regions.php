<?php
require_once __DIR__ . "/../../app/config/path.php";
require_once CORE . 'Session.php';
require_once CONT . "shippingController.php";

Session::configure(1800,'/Ego_website/public/index.php');
Session::startSession();

header('Content-Type: application/json');

try {
    $controller = new shippingController();
    $result = $controller->listShipping();
    
    if ($result['status'] === 'success') {
        echo json_encode([
            'success' => true,
            'regions' => $result['data']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>