<?php 
    require_once __DIR__ . "/../../app/config/path.php";
    require_once CORE . 'Session.php';
    require_once CONT . "UserController.php";
    
    Session::configure(1800,'/Ego_website/public/index.php');
    Session::startSession();

    header('Content-Type: application/json');
    try{
        $controller = new UserController();
        echo json_encode($controller->login());
    }catch(Exception $e){
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
    