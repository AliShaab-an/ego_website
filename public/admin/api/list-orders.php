<?php
    session_start();
    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CORE . 'Auth.php';
    Auth::checkAdmin();

    header('Content-Type: application/json');

    require_once CONT . 'OrderController.php';

    try {
        $controller = new OrderController();
        $result = $controller->getAllOrders();
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
