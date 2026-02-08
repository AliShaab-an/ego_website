<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin', 'editor']);
    $controller = new CategoryController();
    $result = $controller->addCategory();
    Response::json($result);
});
    

    