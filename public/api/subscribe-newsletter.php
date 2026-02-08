<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $controller = new NewsletterController();
    $result = $controller->subscribe();
    Response::json($result);
});
