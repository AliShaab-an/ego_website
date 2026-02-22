<?php

/**
 * PaymentWebhookController
 * 
 * Handles payment gateway responses from Cybersource Secure Acceptance
 * 
 * IMPORTANT: Cybersource Secure Acceptance uses RETURN URLs, not webhooks!
 * - User's browser POSTs to this URL after completing payment
 * - This is NOT a server-to-server webhook
 * - Controller processes payment and redirects user
 * 
 * ENDPOINTS:
 * - POST /api/payment/return/cybersource (return URL for user's browser)
 * - POST /api/payment/cancel/cybersource (cancel URL for user's browser)
 * 
 * CONFIGURATION in Cybersource Dashboard:
 * - Success URL: https://yourdomain.com/api/payment/return/cybersource
 * - Failure URL: https://yourdomain.com/api/payment/return/cybersource  
 * - Cancel URL:  https://yourdomain.com/api/payment/cancel/cybersource
 * 
 * All responses go through return URL for consistent handling.
 */
class PaymentWebhookController
{
    /**
     * Handle Cybersource Secure Acceptance return (user's browser POST)
     * 
     * This endpoint receives POST data from Cybersource via user's browser redirect
     * NOT a server-to-server webhook!
     * 
     * @return void
     */
    public function handleCybersourceReturn(): void
    {
        try {
            // Get POST data from gateway
            $postData = $_POST;
            
            // Log incoming webhook (without sensitive data)
            $logData = $postData;
            unset($logData['signature']); // Don't log signature
            Logger::info("Cybersource webhook received", [
                'decision' => $postData['decision'] ?? 'unknown',
                'transaction_uuid' => $postData['req_transaction_uuid'] ?? 'unknown',
                'reference' => $postData['req_reference_number'] ?? 'unknown'
            ]);
            
            // Initialize service
            $service = new SecureAcceptanceService();
            
            // Process gateway response
            $result = $service->handleGatewayResponse($postData);
            
            // Destroy sensitive data
            $service->destroySensitiveData();
            
            // Determine redirect URL based on result
            $orderId = $result['order_id'];
            $decision = $result['decision'] ?? null;
            
            if ($result['success']) {
                // Payment successful - redirect to order confirmation
                $redirectUrl = "/index.php?page=order-confirmation&order_id={$orderId}&status=success";
                
                // Set success message in session
                if (class_exists('Session')) {
                    Session::set('payment_success', $result['message']);
                }
                
                Logger::info("Payment successful, redirecting", [
                    'order_id' => $orderId,
                    'url' => $redirectUrl
                ]);
                
                $this->redirect($redirectUrl);
                
            } elseif ($decision === 'CANCEL') {
                // Payment cancelled - redirect to checkout with message
                $redirectUrl = "/index.php?page=checkout&status=cancelled";
                
                if (class_exists('Session')) {
                    Session::set('payment_info', $result['message']);
                }
                
                Logger::info("Payment cancelled, redirecting", [
                    'order_id' => $orderId,
                    'url' => $redirectUrl
                ]);
                
                $this->redirect($redirectUrl);
                
            } else {
                // Payment failed - redirect to checkout with error
                $redirectUrl = "/index.php?page=checkout&status=failed";
                
                if (class_exists('Session')) {
                    Session::set('payment_error', $result['message']);
                }
                
                Logger::warning("Payment failed, redirecting", [
                    'order_id' => $orderId,
                    'message' => $result['message'],
                    'url' => $redirectUrl
                ]);
                
                $this->redirect($redirectUrl);
            }
            
        } catch (Exception $e) {
            // Log error
            Logger::error("Webhook processing error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Redirect to generic error page
            if (class_exists('Session')) {
                Session::set('payment_error', 'An error occurred processing your payment. Please contact support.');
            }
            
            $this->redirect('/index.php?page=checkout&status=error');
        }
    }
    
    /**
     * LEGACY METHOD - Kept for backward compatibility
     * Use handleCybersourceReturn() instead
     * 
     * @return void
     * @deprecated Use handleCybersourceReturn()
     */
    public function handleCybersource(): void
    {
        $this->handleCybersourceReturn();
    }
    
    /**
     * Handle Cybersource cancel (when user cancels payment)
     * 
     * @return void
     */
    public function handleCybersourceCancel(): void
    {
        try {
            $postData = $_POST;
            
            Logger::info("Payment cancelled by user", [
                'transaction_uuid' => $postData['req_transaction_uuid'] ?? 'unknown'
            ]);
            
            // Can process cancellation here if needed
            // For now, just redirect to checkout
            
            if (class_exists('Session')) {
                Session::set('payment_info', 'Payment was cancelled. Your order is still pending.');
            }
            
            $this->redirect('/index.php?page=checkout&status=cancelled');
            
        } catch (Exception $e) {
            Logger::error("Error handling payment cancellation", [
                'error' => $e->getMessage()
            ]);
            
            $this->redirect('/index.php?page=checkout&status=error');
        }
    }
    
    /**
     * Handle Cybersource webhook for admin/backend processing (optional)
     * 
     * This can be used for server-to-server notifications (if configured in Cybersource)
     * Returns JSON response instead of redirecting
     * 
     * @return array
     */
    public function processCybersourceWebhook(): array
    {
        try {
            // Get POST data from gateway
            $postData = $_POST;
            
            // Log incoming webhook
            Logger::info("Cybersource webhook (backend) received", [
                'decision' => $postData['decision'] ?? 'unknown',
                'transaction_uuid' => $postData['req_transaction_uuid'] ?? 'unknown'
            ]);
            
            // Initialize service
            $service = new SecureAcceptanceService();
            
            // Process gateway response
            $result = $service->handleGatewayResponse($postData);
            
            // Destroy sensitive data
            $service->destroySensitiveData();
            
            // Return JSON response
            http_response_code(200);
            return $result;
            
        } catch (Exception $e) {
            Logger::error("Webhook (backend) processing error", [
                'error' => $e->getMessage()
            ]);
            
            http_response_code(500);
            return [
                'success' => false,
                'message' => 'Internal server error'
            ];
        }
    }
    
    /**
     * Get order confirmation details (for order-confirmation page)
     * 
     * @return array
     * @throws Exception
     */
    public function getOrderConfirmation(): array
    {
        $orderId = $_GET['order_id'] ?? null;
        
        if (!$orderId) {
            throw new Exception('Order ID is required');
        }
        
        // Get order details
        $order = Order::getById($orderId);
        
        if (!$order) {
            throw new Exception('Order not found');
        }
        
        // Check authorization
        $isGuest = !Auth::check();
        $isOwner = !$isGuest && Auth::id() == $order['user_id'];
        $isAdmin = Authorization::isAdmin();
        
        // For guest orders, allow access for 24 hours after creation
        $isRecentGuest = false;
        if ($isGuest && $order['user_id'] === null) {
            $createdTime = strtotime($order['created_at']);
            $isRecentGuest = (time() - $createdTime) < 86400; // 24 hours
        }
        
        if (!$isOwner && !$isAdmin && !$isRecentGuest) {
            throw new Exception('Unauthorized access to order');
        }
        
        // Get payment transactions for this order
        $transactions = PaymentTransaction::getByOrderId($orderId);
        
        return [
            'success' => true,
            'order' => $order,
            'transactions' => $transactions
        ];
    }
    
    /**
     * Check payment status (AJAX endpoint for order status updates)
     * 
     * @return array
     */
    public function checkPaymentStatus(): array
    {
        try {
            $orderId = $_GET['order_id'] ?? null;
            
            if (!$orderId) {
                throw new Exception('Order ID is required');
            }
            
            // Get order
            $order = Order::getById($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }
            
            // Check authorization
            $isGuest = !Auth::check();
            $isOwner = !$isGuest && Auth::id() == $order['user_id'];
            $isAdmin = Authorization::isAdmin();
            
            if (!$isOwner && !$isAdmin) {
                // For guest, check if order email matches provided email
                $guestEmail = $_GET['email'] ?? null;
                if (!$isGuest || $order['guest_email'] !== $guestEmail) {
                    throw new Exception('Unauthorized access');
                }
            }
            
            // Get latest transaction
            $transactions = PaymentTransaction::getByOrderId($orderId);
            $latestTransaction = !empty($transactions) ? $transactions[0] : null;
            
            return [
                'success' => true,
                'payment_status' => $order['payment_status'],
                'order_status' => $order['status'],
                'paid_at' => $order['paid_at'],
                'transaction' => $latestTransaction ? [
                    'status' => $latestTransaction['status'],
                    'decision' => $latestTransaction['decision'],
                    'message' => $latestTransaction['message']
                ] : null
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Redirect helper
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    private function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
