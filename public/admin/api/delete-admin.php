<?php 

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT . 'UserController.php';
    header('Content-Type: application/json');

    try{
        $controller = new UserController();
        echo json_encode($controller->deleteAdmin()); 
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }