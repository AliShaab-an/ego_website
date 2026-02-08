<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin']);
    $controller = new UserController();
    $result = $controller->deleteAdmin();
    Response::json($result);
});