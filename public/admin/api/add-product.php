<?php 

    require_once __DIR__ . '/../../../app/controllers/ProductController.php';
    require_once __DIR__ . '/../../../app/controllers/ProductVariantController.php';
    require_once __DIR__ . '/../../../app/controllers/ProductImageController.php';

    header('Content-Type: application/json');

    $controller = new ProductController();
    echo json_encode($controller->addProduct());