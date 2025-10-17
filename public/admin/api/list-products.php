<?php 
    require_once __DIR__ . "/../../../app/config/path.php";
    require_once CONT. '/admin/ProductAdminController.php';

    header('Content-Type: application/json');

    try{
        $controller = new ProductAdminController();
        echo json_encode($controller->listProducts());
    }catch (Throwable $e) {
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }