<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireCustomer();
    $controller = new CustomerAccountController();
    $result = $controller->updateProfile();
    Response::json($result);
});
