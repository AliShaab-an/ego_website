<?php 

    require_once __DIR__ . '/../../../app/controllers/UserController.php';
    header('Content-Type: application/json');

    try{
        $controller = new UserController();
        echo json_encode($controller->updateAdmin()); 
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }