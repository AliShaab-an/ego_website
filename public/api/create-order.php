<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    // Allow both guests and customers to place orders
    Authorization::allowGuestOrCustomer();
    $controller = new OrderController();
    $result = $controller->create();
    Response::json($result);
});
