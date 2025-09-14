<?php 
    ini_set('display_errors',1);
    error_reporting(E_ALL);

    require_once __DIR__ . '/../../../app/controllers/UserController.php';

    header('Content-Type: application/json');
    $controller = new UserController();

    $customer = $controller->listCustomers();
    $last7Days = $controller->listCustomersCountLast7Days();

    try{
        echo json_encode([
            'status' => 'success',
            'data' => $customer,
            'last7Days' => $last7Days,
        ]);
        // echo json_encode($controller->listTotalCustomers());
    }catch(Throwable $e){
        echo json_encode(['status' => 'error', 'message' => 'Server error']);
    }