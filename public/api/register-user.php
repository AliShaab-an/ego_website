<?php 

    require_once __DIR__ . "/../../app/controllers/UserController.php";

    header('Content-Type: application/json');

    $controller = new UserController();

    echo json_encode($controller->register());
