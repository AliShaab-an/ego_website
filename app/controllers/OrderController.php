<?php

require_once __DIR__ . '/../config/path.php';
require_once MODELS . 'Order.php';
require_once MODELS . 'Cart.php';
require_once MODELS . 'User.php';
require_once MODELS . 'Coupon.php';
require_once CORE . 'Session.php';

class OrderController {
    
    /**
     * Create a new order (Frontend - Checkout)
     */
    public function create() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Please log in or create an account to complete your order');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            $userId = $_SESSION['user_id'];
            $shippingRegionId = $input['shipping_region_id'] ?? null;
            $paymentMethod = $input['payment_method'] ?? 'COD';
            $couponCode = $input['coupon_code'] ?? null;
            $notes = $input['notes'] ?? '';
            $phone = $input['phone'] ?? '';
            $address = $input['address'] ?? '';
            $city = $input['city'] ?? '';
            $state = $input['state'] ?? '';
            $zip = $input['zip'] ?? '';
            
            // Get coupon ID from code if provided
            $couponId = null;
            if (!empty($couponCode)) {
                $coupon = Coupon::findByCode($couponCode);
                if ($coupon) {
                    $couponId = $coupon['id'];
                }
            }
            
            // Update user contact information if provided
            if (!empty($phone)) {
                User::updateContactInfo($userId, $phone, $address, $city, $state, $zip);
            }
            
            // For Wish Money, shipping region is not required
            if ($paymentMethod === 'cash' && !$shippingRegionId) {
                throw new Exception('Shipping region is required for Cash on Delivery');
            }
            
            // Set default shipping region ID for Wish Money if not provided
            if ($paymentMethod === 'wishmoney' && !$shippingRegionId) {
                $shippingRegionId = 1; // Default region or you can handle differently
            }
            
            $orderId = Order::create($userId, $shippingRegionId, $paymentMethod, $couponId, $notes);
            
            return [
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $orderId
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get order details
     */
    public function getOrder() {
        try {
            $orderId = $_GET['order_id'] ?? null;
            
            if (!$orderId) {
                throw new Exception('Order ID is required');
            }
            
            $order = Order::getById($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Check if user is authorized to view this order
            if (isset($_SESSION['user_id']) && 
                $_SESSION['user_id'] != $order['user_id'] && 
                !in_array($_SESSION['role'] ?? '', ['admin', 'super_admin'])) {
                throw new Exception('Unauthorized access');
            }
            
            return [
                'success' => true,
                'order' => $order
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get user's orders (Frontend)
     */
    public function getUserOrders() {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('Please log in to view your orders');
            }
            
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : 10;
            $offset = ($page - 1) * $limit;
            
            $userId = $_SESSION['user_id'];
            
            $orders = Order::getUserOrders($userId, $limit, $offset);
            $totalCount = Order::countAll('all', $userId);
            $totalPages = ceil($totalCount / $limit);
            
            return [
                'success' => true,
                'orders' => $orders,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_count' => $totalCount,
                    'per_page' => $limit,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get all orders (Admin)
     */
    public function getAllOrders() {
        try {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 20;
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            $offset = ($page - 1) * $limit;
            
            $orders = Order::getAll($limit, $offset, $status);
            $totalCount = Order::countAll($status);
            $totalPages = ceil($totalCount / $limit);
            
            $stats = Order::getStatistics();
            
            return [
                'success' => true,
                'data' => $orders,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_count' => $totalCount,
                    'per_page' => $limit,
                    'has_next' => $page < $totalPages,
                    'has_prev' => $page > 1
                ],
                'statistics' => $stats
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Update order status (Admin)
     */
    public function updateStatus() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            $orderId = $input['order_id'] ?? null;
            $status = $input['status'] ?? null;
            
            if (!$orderId || !$status) {
                throw new Exception('Order ID and status are required');
            }
            
            Order::updateStatus($orderId, $status);
            
            return [
                'success' => true,
                'message' => 'Order status updated successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Update payment status (Admin)
     */
    public function updatePaymentStatus() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            $orderId = $input['order_id'] ?? null;
            $paymentStatus = $input['payment_status'] ?? null;
            
            if (!$orderId || !$paymentStatus) {
                throw new Exception('Order ID and payment status are required');
            }
            
            Order::updatePaymentStatus($orderId, $paymentStatus);
            
            return [
                'success' => true,
                'message' => 'Payment status updated successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Cancel order
     */
    public function cancelOrder() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            $orderId = $input['order_id'] ?? null;
            
            if (!$orderId) {
                throw new Exception('Order ID is required');
            }
            
            // Get order details
            $order = Order::getById($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Check authorization
            $isAdmin = in_array($_SESSION['role'] ?? '', ['admin', 'super_admin']);
            $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $order['user_id'];
            
            if (!$isAdmin && !$isOwner) {
                throw new Exception('Unauthorized');
            }
            
            // Only allow cancellation of pending orders
            if ($order['status'] !== 'pending') {
                throw new Exception('Only pending orders can be cancelled');
            }
            
            Order::cancel($orderId);
            
            return [
                'success' => true,
                'message' => 'Order cancelled successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}