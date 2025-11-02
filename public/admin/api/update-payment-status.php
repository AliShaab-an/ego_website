<?php

    session_start();
    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CORE . 'Auth.php';
    Auth::checkAdmin();

    header('Content-Type: application/json');

    require_once CONT . 'OrderController.php';

    try {
        $controller = new OrderController();
        $result = $controller->updatePaymentStatus();
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode($result);
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
