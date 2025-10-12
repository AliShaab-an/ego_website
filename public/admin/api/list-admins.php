<?php

    require_once __DIR__ . '/../../../app/controllers/UserController.php';

    header('Content-Type: application/json');
    try{
        $controller = new UserController();
        echo json_encode($controller->listAdmins());
    }catch(Exception $e){
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
