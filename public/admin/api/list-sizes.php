<?php 


    require_once __DIR__ . "/../../../app/controllers/SizesController.php";

    header('Content-Type: application/json');

    $controller = new SizesController();
    $sizes = $controller->listSizes();

    echo json_encode([
        "status" => "success",
        "data" => $sizes
    ]);;