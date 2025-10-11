<?php 
    require_once __DIR__ . "/../../../app/controllers/ColorsController.php";

    header('Content-Type: application/json');

    try{
        $controller = new ColorsController();
        echo json_encode($controller->addColor());
    }catch (Throwable $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error: ' . $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);
    }

    

