<?php 

    require_once __DIR__ . "/../../../app/config/path.php";
    require_once CONT . "/ColorsController.php";

    header('Content-Type: application/json');
    try{
        $controller = new ColorsController();
        echo json_encode($controller->listColors());
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
    