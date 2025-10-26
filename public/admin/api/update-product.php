<?php
require_once __DIR__ . '/../../../app/config/path.php';
require_once CONT. '/admin/ProductAdminController.php';
require_once CORE . 'Logger.php';

header('Content-Type: application/json');

try{
    $controller = new ProductAdminController();
    $response = $controller->updateProduct();


    echo json_encode($response);
}catch(Exception $e){
    Logger::error("update-product.php", $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>