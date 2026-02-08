<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Response::error('Invalid request method', null, 405);
    }

    $controller = new UserController();
    $result = $controller->adminLogin();
    Response::json($result);
});
