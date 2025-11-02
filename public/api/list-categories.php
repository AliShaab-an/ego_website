<?php
    require_once __DIR__ . '/../../app/config/path.php';
    require_once CONT . 'CategoryController.php';

    header('Content-Type: application/json');

    try{
        $controller = new CategoryController();
        echo json_encode($controller->listCategories());
    }catch(Exception $e){
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
    }
    