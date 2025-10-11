<?php
    require_once __DIR__ . "/../../app/config/path.php";
    require_once CONT . "CartController.php";
    require_once CORE . "Logger.php";

    header('Content-Type: application/json');

    file_put_contents(__DIR__ . "/../../logs/error.log", "API file reached\n", FILE_APPEND);

    try{
        $controller = new CartController();
        $response = $controller->addToCart();

        file_put_contents(__DIR__ . '/../../logs/error.log', "Response: " . print_r($response, true) . "\n", FILE_APPEND);

        echo json_encode($response);
    }catch(Throwable $e){
        Logger::error("add-to-cart.php", $e->getMessage());
        echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    }
    

    

    