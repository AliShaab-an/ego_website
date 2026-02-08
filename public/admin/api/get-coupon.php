<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin', 'editor']);
    $controller = new CouponController();
    $result = $controller->getCoupon();
    Response::json($result);
});