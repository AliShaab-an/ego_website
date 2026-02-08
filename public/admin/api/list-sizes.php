<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin', 'editor']);
    $controller = new SizesController();
    $result = $controller->listSizes();
    Response::json($result);
});
    