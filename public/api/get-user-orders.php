<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new OrderController();
    $result = $controller->getUserOrders();
    Response::json($result);
});
