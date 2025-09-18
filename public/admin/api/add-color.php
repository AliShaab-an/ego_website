<?php 


    require_once __DIR__ . "/../../../app/controllers/ColorsController.php";

    header('Content-Type: application/json');

    $controller = new ColorsController();

    echo json_encode($controller->addColor());

