<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::allowGuestOrCustomer();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Response::error('Invalid request method', null, 405);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    
    $code = $data['code'] ?? '';
    $orderTotal = $data['orderTotal'] ?? 0;

    if (empty($code)) {
        Response::error('Coupon code is required', null, 400);
    }

    if ($orderTotal <= 0) {
        Response::error('Invalid order total', null, 400);
    }

    $result = Coupon::validateCoupon($code, $orderTotal);

    if ($result['valid']) {
        Response::json([
            'success' => true,
            'message' => $result['message'],
            'discount' => $result['discount'],
            'discount_type' => $result['discount_type'],
            'discount_value' => $result['discount_value']
        ]);
    } else {
        Response::json([
            'success' => false,
            'message' => $result['message']
        ]);
    }
});
