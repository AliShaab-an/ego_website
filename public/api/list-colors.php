<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    // Public endpoint - no authentication required for shop filters
    $controller = new ColorsController();
    $result = $controller->listColors();
    Response::json($result);
});
