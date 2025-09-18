<?php 


    require_once __DIR__ . "/../../../app/controllers/ColorsController.php";

    header('Content-Type: application/json');

    $controller = new ColorsController();
    $colors = $controller->listColors();

    echo json_encode([
        "status" => "success",
        "data" => $colors
    ]);;