<?php 

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT. 'ProductImagesController.php';
    require_once CORE . 'Logger.php';

    header('Content-Type: application/json');

    try {
        if (empty($_POST['product_id'])) {
            throw new Exception("Missing product_id");
        }

        $controller = new ProductImagesController();
        $response = $controller->uploadImages(
            $_POST['product_id'],
            $_FILES['images']
        );

        echo json_encode($response);
    } catch (Exception $e) {
        Logger::error("add-product-images.php", $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
