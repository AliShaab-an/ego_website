<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    // Public endpoint - no authentication required for shop filters
    $controller = new SizesController();
    $result = $controller->listSizes();
    Response::json($result);
});
