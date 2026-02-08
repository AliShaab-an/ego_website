<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new ProductController();
    $result = $controller->listProducts();
    Response::json($result);
});