<?php 

    ini_set('display_errors',1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../../../app/controllers/UserController.php';

    header('Content-Type: application/json');

    $controller = new UserController();

    try{
        echo json_encode($controller->listAdmins());
    }catch(Throwable $e){
        echo json_encode(['status' => 'error', 'message' => 'Server error']);
    }