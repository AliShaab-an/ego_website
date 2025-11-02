<?php 
    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT . 'CategoryController.php';
    header('Content-Type: application/json');

    try{
        $controller = new CategoryController();
        echo json_encode($controller->addCategory()); 
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
    

    