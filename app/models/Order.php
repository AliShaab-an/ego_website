<?php

require_once __DIR__ . '/../core/DB.php';

class Order {
    
    /**
     * Create a new order from cart
     */
    public static function create($userId, $shippingRegionId, $paymentMethod, $couponId = null, $notes = '') {
        try {
            DB::getConnection()->beginTransaction();
            
            // Insert order
            $sql = "INSERT INTO orders (user_id, shipping_region_id, payment_method, coupon_id, payment_status, status, created_at) 
                    VALUES (?, ?, ?, ?, 'pending', 'pending', NOW())";
            
            DB::query($sql, [$userId, $shippingRegionId, $paymentMethod, $couponId]);
            $orderId = DB::getConnection()->lastInsertId();
            
            // Get cart items
            $cartId = self::getCartId($userId);
            if (!$cartId) {
                throw new Exception("Cart not found");
            }
            
            $cartItems = self::getCartItems($cartId);
            
            if (empty($cartItems)) {
                throw new Exception("Cart is empty");
            }
            
            // Insert order items
            foreach ($cartItems as $item) {
                $itemSql = "INSERT INTO order_items (order_id, variant_id, quantity, price, discount, note) 
                           VALUES (?, ?, ?, ?, ?, ?)";
                
                // Calculate actual discount amount from percentage
                $discountPercentage = $item['discount'] ?? 0;
                $itemPrice = $item['price'] ?? 0;
                $discountAmount = ($itemPrice * $discountPercentage) / 100;
                
                DB::query($itemSql, [
                    $orderId,
                    $item['variant_id'],
                    $item['quantity'],
                    $itemPrice,
                    $discountAmount,
                    $notes
                ]);
                
                // Update product variant stock
                self::updateStock($item['variant_id'], $item['quantity']);
            }
            
            // Clear cart after order
            self::clearCart($cartId);
            
            DB::getConnection()->commit();
            
            return $orderId;
            
        } catch (Exception $e) {
            DB::getConnection()->rollBack();
            throw new Exception("Failed to create order: " . $e->getMessage());
        }
    }
    
    /**
     * Get order by ID with full details
     */
    public static function getById($orderId) {
        try {
            $sql = "SELECT o.*, 
                           u.name as customer_name, 
                           u.email as customer_email,
                           u.phone as customer_phone,
                           u.address, u.city, u.state, u.country, u.zip_code,
                           s.region_name, s.fee_per_kg as shipping_fee,
                           c.code as coupon_code, c.discount_type, c.discount_value
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN shipping_regions s ON o.shipping_region_id = s.id
                    LEFT JOIN coupons c ON o.coupon_id = c.id
                    WHERE o.id = ?";
                    
            $stmt = DB::query($sql, [$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                $order['items'] = self::getOrderItems($orderId);
                $order['totals'] = self::calculateOrderTotals($order);
            }
            
            return $order;
            
        } catch (Exception $e) {
            throw new Exception("Failed to fetch order: " . $e->getMessage());
        }
    }
    
    /**
     * Get order items
     */
    public static function getOrderItems($orderId) {
        try {
            $sql = "SELECT oi.*, 
                           p.name as product_name,
                           p.base_price,
                           pv.price as variant_price,
                           pi.image_path,
                           c.name as color_name,
                           s.name as size_name
                    FROM order_items oi
                    INNER JOIN product_variants pv ON oi.variant_id = pv.id
                    INNER JOIN products p ON pv.product_id = p.id
                    LEFT JOIN colors c ON pv.color_id = c.id
                    LEFT JOIN sizes s ON pv.size_id = s.id
                    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                    WHERE oi.order_id = ?
                    ORDER BY oi.id";
                    
            $stmt = DB::query($sql, [$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            throw new Exception("Failed to fetch order items: " . $e->getMessage());
        }
    }
    
    /**
     * Get all orders with pagination and filters
     */
    public static function getAll($limit = 50, $offset = 0, $status = 'all', $userId = null) {
        try {
            $whereConditions = [];
            $params = [];
            
            if ($status !== 'all') {
                $whereConditions[] = "o.status = ?";
                $params[] = $status;
            }
            
            if ($userId) {
                $whereConditions[] = "o.user_id = ?";
                $params[] = $userId;
            }
            
            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
            
            $sql = "SELECT o.*, 
                           u.name as customer_name, 
                           u.email as customer_email,
                           COUNT(oi.id) as items_count
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN order_items oi ON o.id = oi.order_id
                    $whereClause
                    GROUP BY o.id
                    ORDER BY o.created_at DESC
                    LIMIT ? OFFSET ?";
            
            $params[] = (int)$limit;
            $params[] = (int)$offset;
            
            $stmt = DB::query($sql, $params);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate totals for each order
            foreach ($orders as &$order) {
                $orderDetails = self::getById($order['id']);
                $order['total'] = $orderDetails['totals']['grand_total'];
            }
            
            return $orders;
            
        } catch (Exception $e) {
            throw new Exception("Failed to fetch orders: " . $e->getMessage());
        }
    }
    
    /**
     * Count orders
     */
    public static function countAll($status = 'all', $userId = null) {
        try {
            $whereConditions = [];
            $params = [];
            
            if ($status !== 'all') {
                $whereConditions[] = "status = ?";
                $params[] = $status;
            }
            
            if ($userId) {
                $whereConditions[] = "user_id = ?";
                $params[] = $userId;
            }
            
            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
            
            $stmt = DB::query("SELECT COUNT(*) as count FROM orders $whereClause", $params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$row['count'];
            
        } catch (Exception $e) {
            throw new Exception("Failed to count orders: " . $e->getMessage());
        }
    }
    
    /**
     * Update order status
     */
    public static function updateStatus($orderId, $status) {
        try {
            $validStatuses = ['pending', 'shipped', 'completed', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                throw new Exception("Invalid status");
            }
            
            $sql = "UPDATE orders SET status = ? WHERE id = ?";
            DB::query($sql, [$status, $orderId]);
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception("Failed to update order status: " . $e->getMessage());
        }
    }
    
    /**
     * Update payment status
     */
    public static function updatePaymentStatus($orderId, $paymentStatus) {
        try {
            $validStatuses = ['pending', 'paid', 'failed'];
            if (!in_array($paymentStatus, $validStatuses)) {
                throw new Exception("Invalid payment status");
            }
            
            $sql = "UPDATE orders SET payment_status = ? WHERE id = ?";
            DB::query($sql, [$paymentStatus, $orderId]);
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception("Failed to update payment status: " . $e->getMessage());
        }
    }
    
    /**
     * Cancel order
     */
    public static function cancel($orderId) {
        try {
            DB::getConnection()->beginTransaction();
            
            // Get order items to restore stock
            $items = self::getOrderItems($orderId);
            
            foreach ($items as $item) {
                self::restoreStock($item['variant_id'], $item['quantity']);
            }
            
            // Update order status
            self::updateStatus($orderId, 'cancelled');
            
            DB::getConnection()->commit();
            
            return true;
            
        } catch (Exception $e) {
            DB::getConnection()->rollBack();
            throw new Exception("Failed to cancel order: " . $e->getMessage());
        }
    }
    
    /**
     * Get user's orders
     */
    public static function getUserOrders($userId, $limit = 20, $offset = 0) {
        return self::getAll($limit, $offset, 'all', $userId);
    }
    
    // Helper methods
    
    private static function getCartId($userId) {
        $stmt = DB::query("SELECT cart_id FROM cart WHERE user_id = ?", [$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['cart_id'] : null;
    }
    
    private static function getCartItems($cartId) {
        $sql = "SELECT ci.*, 
                       CASE 
                           WHEN pv.price IS NOT NULL AND pv.price > 0 THEN pv.price
                           ELSE p.base_price
                       END as price,
                       CASE 
                           WHEN pd.is_active = 1 AND pd.discount_percentage > 0 
                           THEN pd.discount_percentage 
                           ELSE 0 
                       END as discount
                FROM cart_item ci
                INNER JOIN product_variants pv ON ci.variant_id = pv.id
                INNER JOIN products p ON ci.product_id = p.id
                LEFT JOIN product_discounts pd ON p.id = pd.product_id
                WHERE ci.cart_id = ?";
                
        $stmt = DB::query($sql, [$cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private static function clearCart($cartId) {
        DB::query("DELETE FROM cart_item WHERE cart_id = ?", [$cartId]);
    }
    
    private static function updateStock($variantId, $quantity) {
        $sql = "UPDATE product_variants SET quantity = quantity - ? WHERE id = ? AND quantity >= ?";
        DB::query($sql, [$quantity, $variantId, $quantity]);
    }
    
    private static function restoreStock($variantId, $quantity) {
        $sql = "UPDATE product_variants SET quantity = quantity + ? WHERE id = ?";
        DB::query($sql, [$quantity, $variantId]);
    }
    
    private static function calculateOrderTotals($order) {
        $subtotal = 0;
        $discount = 0;
        
        foreach ($order['items'] as $item) {
            // If stored price is 0, use variant_price or base_price
            $itemPrice = $item['price'];
            if ($itemPrice == 0) {
                $itemPrice = ($item['variant_price'] && $item['variant_price'] > 0) 
                    ? $item['variant_price'] 
                    : $item['base_price'];
            }
            
            $itemTotal = $itemPrice * $item['quantity'];
            $subtotal += $itemTotal;
            
            // Discount is now stored as actual amount, not percentage
            if ($item['discount'] > 0) {
                $discount += $item['discount'] * $item['quantity'];
            }
        }
        
        $shippingFee = $order['shipping_fee'] ?? 0;
        
        // Apply coupon discount if exists
        $couponDiscount = 0;
        if (isset($order['discount_value']) && $order['discount_value'] > 0) {
            if ($order['discount_type'] === 'percentage') {
                $couponDiscount = ($subtotal * $order['discount_value'] / 100);
            } else {
                // fixed discount
                $couponDiscount = $order['discount_value'];
            }
        }
        
        $grandTotal = $subtotal - $discount - $couponDiscount + $shippingFee;
        
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon_discount' => $couponDiscount,
            'shipping_fee' => $shippingFee,
            'grand_total' => $grandTotal
        ];
    }
    
    /**
     * Get order statistics
     */
    public static function getStatistics() {
        try {
            $stats = [];
            
            $stats['total'] = self::countAll();
            $stats['pending'] = self::countAll('pending');
            $stats['shipped'] = self::countAll('shipped');
            $stats['completed'] = self::countAll('completed');
            $stats['cancelled'] = self::countAll('cancelled');
            
            // Get total revenue (price * quantity - discount)
            $sql = "SELECT SUM(oi.price * oi.quantity - oi.discount * oi.quantity) as revenue 
                    FROM order_items oi
                    INNER JOIN orders o ON oi.order_id = o.id
                    WHERE o.status != 'cancelled'";
            $stmt = DB::query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['revenue'] = $result['revenue'] ?? 0;
            
            return $stats;
            
        } catch (Exception $e) {
            throw new Exception("Failed to get statistics: " . $e->getMessage());
        }
    }
}
