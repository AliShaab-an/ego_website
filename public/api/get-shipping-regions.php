<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new shippingController();
    $result = $controller->listShipping();
    
    if ($result['status'] === 'success') {
        Response::json([
            'success' => true,
            'regions' => $result['data']
        ]);
    } else {
        Response::json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
});