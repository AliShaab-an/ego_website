<?php

require_once __DIR__ . '/../core/DB.php';

class Dashboard {
    
    /**
     * Get dashboard statistics
     */
    public static function getStatistics() {
        try {
            $stats = [];
            
            // Get order statistics
            try {
                $stats['shippedOrders'] = self::getOrderCountByStatus('shipped');
                $stats['pendingOrders'] = self::getOrderCountByStatus('pending');
                $stats['newOrders'] = self::getOrderCountByStatus('completed');
            } catch (Exception $e) {
                error_log("Dashboard order stats error: " . $e->getMessage());
                $stats['shippedOrders'] = 0;
                $stats['pendingOrders'] = 0;
                $stats['newOrders'] = 0;
            }
            
            // Get customer count
            try {
                $stats['totalCustomers'] = self::getTotalCustomers();
            } catch (Exception $e) {
                error_log("Dashboard customer stats error: " . $e->getMessage());
                $stats['totalCustomers'] = 0;
            }
            
            // Get product statistics
            try {
                $stats['totalProducts'] = self::getTotalProducts();
                $stats['stockProducts'] = self::getStockProducts();
                $stats['outOfStock'] = $stats['totalProducts'] - $stats['stockProducts'];
            } catch (Exception $e) {
                error_log("Dashboard product stats error: " . $e->getMessage());
                $stats['totalProducts'] = 0;
                $stats['stockProducts'] = 0;
                $stats['outOfStock'] = 0;
            }
            
            // Get total revenue
            try {
                $stats['totalRevenue'] = self::getTotalRevenue();
            } catch (Exception $e) {
                error_log("Dashboard revenue stats error: " . $e->getMessage());
                $stats['totalRevenue'] = 0;
            }
            
            // Get weekly order data
            try {
                $stats['weeklyData'] = self::getWeeklyOrderData();
            } catch (Exception $e) {
                error_log("Dashboard weekly data error: " . $e->getMessage());
                $stats['weeklyData'] = [0, 0, 0, 0, 0, 0, 0];
            }
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
            error_log("Dashboard stats trace: " . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Get order count by status
     */
    private static function getOrderCountByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE status = ?";
        $result = DB::query($sql, [$status])->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
    
    /**
     * Get total customers
     */
    private static function getTotalCustomers() {
        $sql = "SELECT COUNT(*) as count FROM users WHERE role = 'customer'";
        $result = DB::query($sql)->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
    
    /**
     * Get total products
     */
    private static function getTotalProducts() {
        $sql = "SELECT COUNT(*) as count FROM products";
        $result = DB::query($sql)->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
    
    /**
     * Get products in stock
     */
    private static function getStockProducts() {
        $sql = "SELECT COUNT(DISTINCT p.id) as count 
                FROM products p 
                INNER JOIN product_variants pv ON p.id = pv.product_id 
                WHERE pv.quantity > 0";
        $result = DB::query($sql)->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
    
    /**
     * Get total revenue
     */
    private static function getTotalRevenue() {
        $sql = "SELECT COALESCE(SUM(oi.price * oi.quantity - oi.discount), 0) as revenue 
                FROM orders o
                INNER JOIN order_items oi ON o.id = oi.order_id
                WHERE o.status != 'cancelled'";
        $result = DB::query($sql)->fetch(PDO::FETCH_ASSOC);
        return (float)($result['revenue'] ?? 0);
    }
    
    /**
     * Get weekly order data (last 7 days)
     */
    private static function getWeeklyOrderData() {
        $sql = "SELECT 
                    DAYOFWEEK(created_at) as day_of_week,
                    COUNT(*) as order_count
                FROM orders 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DAYOFWEEK(created_at)
                ORDER BY DAYOFWEEK(created_at)";
        
        $result = DB::query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize all days with 0
        $weeklyOrders = [0, 0, 0, 0, 0, 0, 0]; // Sun-Sat
        
        // Fill in the actual data
        foreach ($result as $row) {
            $dayIndex = $row['day_of_week'] - 1; // MySQL DAYOFWEEK returns 1-7 (Sun-Sat)
            $weeklyOrders[$dayIndex] = (int)$row['order_count'];
        }
        
        return $weeklyOrders;
    }
}
