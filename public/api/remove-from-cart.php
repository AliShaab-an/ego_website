<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new CartController();
    $result = $controller->removeFromCart();
    Response::json($result);
});