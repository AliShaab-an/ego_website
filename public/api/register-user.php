<?php 

    require_once __DIR__ . "/../../app/config/path.php";
    require_once CORE . 'Session.php';
    require_once CONT . "UserController.php";
    
    Session::configure(1800, url('index.php'));
    Session::startSession();

    header('Content-Type: application/json');
    try{
        $controller = new UserController();
        echo json_encode($controller->register());
    }catch(Exception $e){
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
    }
    
