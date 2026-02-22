<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::allowGuestOrCustomer();
    $controller = new CartController();
    $result = $controller->removeFromCart();
    Response::json($result);
});