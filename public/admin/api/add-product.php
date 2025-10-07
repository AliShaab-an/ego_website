<?php

use PgSql\Lob;

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT. 'ProductController.php';
    require_once CORE . 'Logger.php';
    
    file_put_contents(__DIR__ . '/../../../logs/controller.log', "API called\n", FILE_APPEND);
    header('Content-Type: application/json');
    try{
        $controller = new ProductController();
        echo json_encode($controller->addProduct());
    }catch(Exception $e){
        Logger::error("add-product.php", $e->getMessage());
        echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    }
    