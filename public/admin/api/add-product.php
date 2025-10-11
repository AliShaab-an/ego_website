<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT. 'ProductController.php';
    require_once CORE . 'Logger.php';
    

    header('Content-Type: application/json');

    try{
        $controller = new ProductController();
        $response = $controller->addProduct();

        file_put_contents(__DIR__ . '/../../../logs/error.log', "Response: " . print_r($response, true) . "\n", FILE_APPEND);

        echo json_encode($response);
    }catch(Exception $e){
        Logger::error("add-product.php", $e->getMessage());
        echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    }
    