<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ . "/../../app/controllers/cartController.php";
    header('Content-Type: application/json');

    file_put_contents(__DIR__ . "/../../logs/debug.log", "API file reached\n", FILE_APPEND);


    try{
        $controller = new CartController();
        file_put_contents(__DIR__ . "/../../logs/debug.log", "Controller instantiated\n", FILE_APPEND);
        $response = $controller->addToCart();

        file_put_contents(__DIR__ . "/../../logs/debug.log", "addToCart returned: " . print_r($response, true) . "\n", FILE_APPEND);

        echo json_encode($response);
    }catch(Throwable $e){
        file_put_contents(__DIR__ . "/../../logs/debug.log", "Caught Exception: " . $e->getMessage() . "\n", FILE_APPEND);
        
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
    

    

    