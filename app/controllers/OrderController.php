<?php

    class OrderController {
        
        /**
         * Create a new order (Frontend - Checkout)
         */
        public function create() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Support both logged-in users and guests
            $isGuest = !Auth::check();
            $userId = $isGuest ? null : Auth::id();
            
            // Guest information (required for guests)
            $guestName = $input['name'] ?? '';
            $guestEmail = $input['email'] ?? '';
            $guestPhone = $input['phone'] ?? '';
            
            // Validate guest information
            if ($isGuest) {
                if (empty($guestName) || empty($guestEmail) || empty($guestPhone)) {
                    throw new Exception('Name, email, and phone are required for guest checkout');
                }
                if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Please provide a valid email address');
                }
            }
            
            $shippingRegionId = $input['shipping_region_id'] ?? null;

            // Normalise payment method to lowercase; validate against known keys from settings
            $rawMethod     = strtolower(trim($input['payment_method'] ?? 'cod'));
            $allowedMethods = ['cod', 'bank', 'omt', 'wishmoney'];
            $paymentMethod  = in_array($rawMethod, $allowedMethods) ? $rawMethod : 'cod';

            $couponCode = $input['coupon_code'] ?? null;
            $notes = $input['notes'] ?? '';
            $phone = $isGuest ? $guestPhone : ($input['phone'] ?? '');
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
            
            // Update user contact information if logged-in and provided
            if (!$isGuest && !empty($phone)) {
                User::updateContactInfo($userId, $phone, $address, $city, $state, $zip);
            }
            
            // Shipping region required for all delivery methods
            if (in_array($paymentMethod, ['cod', 'bank', 'omt', 'wishmoney']) && !$shippingRegionId) {
                throw new Exception('Shipping region is required');
            }
            
            // Prepare guest info array
            $guestInfo = $isGuest ? [
                'name' => $guestName,
                'email' => $guestEmail,
                'phone' => $guestPhone,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip
            ] : null;
            
            $orderId = Order::create($userId, $shippingRegionId, $paymentMethod, $couponId, $notes, $guestInfo);
            
            // Send order confirmation email
            try {
                $orderData = Order::getById($orderId);
                $customerEmail = $isGuest ? $guestEmail : (Auth::user()['email'] ?? '');
                $customerName  = $isGuest ? $guestName  : (Auth::user()['name'] ?? 'Customer');
                $customerPhone = $isGuest ? $guestPhone  : ($input['phone'] ?? '');
                $orderNumber   = 'ORD-' . str_pad($orderId, 6, '0', STR_PAD_LEFT);

                if (!empty($customerEmail) && $orderData) {
                    // Email to customer
                    $customerHtml = EmailService::renderTemplate('order-confirmation', [
                        'order'        => $orderData,
                        'items'        => $orderData['items'] ?? [],
                        'customerName' => $customerName,
                        'orderNumber'  => $orderNumber,
                    ]);
                    EmailService::send($customerEmail, "Order Confirmation #$orderNumber", $customerHtml);
                }

                // Email to admin/store owner
                $adminEmail = getSetting('contact_email', '');
                if (!empty($adminEmail) && $orderData) {
                    $adminHtml = EmailService::renderTemplate('order-new-admin', [
                        'order'         => $orderData,
                        'items'         => $orderData['items'] ?? [],
                        'customerName'  => $customerName,
                        'customerEmail' => $customerEmail,
                        'customerPhone' => $customerPhone,
                        'orderNumber'   => $orderNumber,
                    ]);
                    EmailService::send($adminEmail, "New Order #$orderNumber", $adminHtml);
                }
            } catch (Exception $emailErr) {
                // Log but don't fail the order
                error_log("Order email failed: " . $emailErr->getMessage());
            }
            
            // For eCheck/Bank Transfer payment, redirect to Secure Acceptance
            // Accept both 'echeck' and 'bank' as payment method identifiers
            if ($paymentMethod === 'echeck' || $paymentMethod === 'bank') {
                return $this->initiateEcheckPayment($orderId, $userId, $guestInfo);
            }
            
            return [
                'success' => true,
                'message' => $isGuest 
                    ? 'Order placed successfully! Check your email for order confirmation.' 
                    : 'Order placed successfully!',
                'order_id' => $orderId,
                'order_number' => 'ORD-' . str_pad($orderId, 6, '0', STR_PAD_LEFT)
            ];
        }
        
        /**
         * Initiate eCheck payment via Cybersource Secure Acceptance
         * 
         * @param int $orderId Order ID
         * @param int|null $userId User ID (null for guest)
         * @param array|null $guestInfo Guest information
         * @return array Checkout data with URL and form fields
         * @throws Exception
         */
        private function initiateEcheckPayment(int $orderId, ?int $userId, ?array $guestInfo): array
        {
            try {
                // Get order details
                $order = Order::getById($orderId);
                
                if (!$order) {
                    throw new Exception('Order not found');
                }
                
                // Get user details if logged in
                $user = null;
                if ($userId) {
                    $user = User::findById($userId);
                }
                
                // Initialize Secure Acceptance service
                $service = new SecureAcceptanceService();
                
                // Build checkout request
                $checkoutData = $service->buildEcheckCheckoutRequest($order, $user);
                
                // Return checkout data (frontend will auto-submit form)
                return [
                    'success' => true,
                    'payment_gateway' => 'cybersource',
                    'checkout_url' => $checkoutData['url'],
                    'checkout_fields' => $checkoutData['fields'],
                    'order_id' => $orderId,
                    'order_number' => 'ORD-' . str_pad($orderId, 6, '0', STR_PAD_LEFT),
                    'message' => 'Redirecting to secure payment gateway...'
                ];
                
            } catch (Exception $e) {
                Logger::error("Failed to initiate eCheck payment", [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
                
                throw new Exception('Failed to initiate payment: ' . $e->getMessage());
            }
        }
        
        /**
         * Get order details
         */
        public function getOrder() {
            $orderId = $_GET['order_id'] ?? null;
            
            if (!$orderId) {
                throw new Exception('Order ID is required');
            }
            
            $order = Order::getById($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Check if user is authorized to view this order
            // Allow admin/super_admin/editor OR customer viewing their own order
            $isAdmin = Authorization::isAdmin() || Authorization::hasRole('editor');
            $isOwner = Auth::check() && Auth::id() == $order['user_id'];
            
            if (!$isAdmin && !$isOwner) {
                throw new Exception('Unauthorized access');
            }
            
            return [
                'success' => true,
                'order' => $order
            ];
        }
        
        /**
         * Get user's orders (Frontend)
         */
        public function getUserOrders() {
            if (!Auth::check()) {
                throw new Exception('Please log in to view your orders');
            }
            
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(50, (int)$_GET['limit'])) : 10;
            $offset = ($page - 1) * $limit;
            
            $userId = Auth::id();
            
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
        }
        
        /**
         * Get all orders (Admin)
         */
        public function getAllOrders() {
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? max(1, min(100, (int)$_GET['limit'])) : 5;
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
        }
        
        /**
         * Update order status (Admin)
         */
        public function updateStatus() {
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
        }
        
        /**
         * Update payment status (Admin)
         */
        public function updatePaymentStatus() {
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
        }
        
        /**
         * Cancel order
         */
        public function cancelOrder() {
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
            $isOwner = Auth::check() && Auth::id() == $order['user_id'];
            
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
        }
    }