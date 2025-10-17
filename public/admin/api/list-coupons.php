<?php 


    require_once __DIR__ . "/../../../app/controllers/CouponController.php";

    header('Content-Type: application/json');
    try{
        $controller = new CouponController();
        echo json_encode($controller->listCoupons());
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }