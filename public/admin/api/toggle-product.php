<?php

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT. '/admin/ProductAdminController.php';
    
    header('Content-Type: application/json');

    try {
        $controller = new ProductAdminController();
        echo json_encode($controller->toggleProductStatus());
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }