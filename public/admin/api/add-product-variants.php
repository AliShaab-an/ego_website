<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT. 'ProductVariantsController.php';
    require_once CORE . 'Logger.php';

    header('Content-Type: application/json');

    try{
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['product_id']) || empty($data['variants'])) {
        throw new Exception("Missing product_id or variants data");
        }

        $controller = new ProductVariantsController();
        $response = $controller->addProductVariants($data['product_id'], $data['variants']); // ✅ new method
        echo json_encode($response);
    }catch(Exception $e){
        Logger::error("add-product-variants.php", $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }