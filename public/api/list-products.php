<?php 
    require_once __DIR__ . "/../../app/config/path.php";
    require_once CONT. '/frontend/ProductController.php';

    header('Content-Type: application/json');

    try{
        $controller = new ProductController();
        echo json_encode($controller->listProducts());
    }catch (Throwable $e) {
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }