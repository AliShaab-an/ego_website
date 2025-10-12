<?php 


    require_once __DIR__ . "/../../../app/controllers/SizesController.php";

    header('Content-Type: application/json');
    try{
        $controller = new  SizesController();
        echo json_encode($controller->addSize());
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
    
    