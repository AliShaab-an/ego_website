<?php 


    require_once __DIR__ . '/../../../app/controllers/CategoryController.php';

    header('Content-Type: application/json');
    try{
        $controller = new CategoryController();
        echo json_encode($controller->deleteCategory());
    }catch(Throwable $e){
        echo json_encode([
            'status' => 'error',
            'message' => 'Internal server error: ' . $e->getMessage(),
            'trace'   => $e->getTraceAsString()
        ]);
    }
    