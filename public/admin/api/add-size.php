<?php 


    require_once __DIR__ . "/../../../app/controllers/SizesController.php";

    header('Content-Type: application/json');

    $controller = new  SizesController();

    echo json_encode($controller->addSize());