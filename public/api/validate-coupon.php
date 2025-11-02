<?php
    require_once __DIR__ . '/../../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once MODELS . 'Coupon.php';

    Session::configure(1800, url('index.php'), true);
    Session::startSession();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $code = $data['code'] ?? '';
        $orderTotal = $data['orderTotal'] ?? 0;

        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Coupon code is required']);
            exit;
        }

        if ($orderTotal <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid order total']);
            exit;
        }

        $result = Coupon::validateCoupon($code, $orderTotal);

        if ($result['valid']) {
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'discount' => $result['discount'],
                'discount_type' => $result['discount_type'],
                'discount_value' => $result['discount_value']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $result['message']
            ]);
        }

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error validating coupon: ' . $e->getMessage()
        ]);
    }
