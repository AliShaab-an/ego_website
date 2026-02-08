<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new UserController();
    $result = $controller->login();
    Response::json($result);
});
    