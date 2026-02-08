<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new CartController();
    $result = $controller->addToCart();
    Response::json($result);
});
    

    

    