<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin']);

    $data = json_decode(file_get_contents('php://input'), true);
    $orderId = isset($data['order_id']) ? (int)$data['order_id'] : 0;

    if ($orderId <= 0) {
        Response::json(['success' => false, 'message' => 'Invalid order ID']);
        return;
    }

    Order::delete($orderId);

    Response::json(['success' => true, 'message' => 'Order deleted successfully']);
});
