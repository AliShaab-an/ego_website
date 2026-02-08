<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new ContactMessageController();
    $result = $controller->submit();
    Response::json($result);
});
