<?php
    require_once __DIR__ . "/../../app/config/path.php";
    require_once CORE . 'Session.php';
    require_once CONT . "CartController.php";
    
    Session::configure(1800,'/Ego_website/public/index.php');
    Session::startSession();

    header('Content-Type: application/json');

    try{
        $controller = new CartController();
        echo json_encode($controller->getCartItems());
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }