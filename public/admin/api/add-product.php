<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

    require_once __DIR__ . '/../../../app/controllers/ProductController.php';
    

    header('Content-Type: application/json');
    try{
        $controller = new ProductController();
        echo json_encode($controller->addProduct());
    }catch(Exception $e){
        echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    }
    