<?php

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT . 'CouponController.php';

    header('Content-Type: application/json');

    try {
        $controller = new CouponController();
        echo json_encode($controller->updateCoupon());
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }