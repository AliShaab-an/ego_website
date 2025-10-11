<?php 


    require_once __DIR__ . "/../../../app/controllers/SizesController.php";

    header('Content-Type: application/json');
    try{
        $controller = new  SizesController();
        echo json_encode($controller->addSize());
    }catch(Throwable $e){
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error: ' . $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);
    }
    
    