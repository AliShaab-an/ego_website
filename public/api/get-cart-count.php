<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new CartController();
    $result = $controller->getCartCount();
    Response::json($result);
});