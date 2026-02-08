<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin', 'editor']);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Response::error('Invalid request method', null, 405);
    }

    $controller = new shippingController();
    $result = $controller->toggleStatus();
    Response::json($result);
});