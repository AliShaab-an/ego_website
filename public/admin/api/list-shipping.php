<?php 


    require_once __DIR__ . "/../../../app/controllers/ShippingController.php";

    header('Content-Type: application/json');
    try{
        $controller = new ShippingController();
        echo json_encode($controller->listShipping());
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }