<?php

    require_once __DIR__ . '/../core/DB.php';

class Coupon
{
    public static function create($code, $discountType, $discountValue, $startDate, $endDate, $minOrderValue, $isActive){
        try {
            DB::query(
                "INSERT INTO coupons (code, discount_type, discount_value, start_date, end_date, min_order_value, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$code, $discountType, $discountValue, $startDate, $endDate, $minOrderValue, $isActive]
            );
            return DB::getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Failed to create coupon: " . $e->getMessage());
        }
    }

    
    public static function getAll(){
        try {
            $stmt = DB::query("SELECT * FROM coupons ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to fetch coupons: " . $e->getMessage());
        }
    }

    public static function findById($id){
        try {
            $stmt = DB::query("SELECT * FROM coupons WHERE id = ? LIMIT 1", [$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to find coupon: " . $e->getMessage());
        }
    }

    public static function findByCode($code){
        try {
            $stmt = DB::query("SELECT * FROM coupons WHERE code = ? LIMIT 1", [$code]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error checking coupon code: " . $e->getMessage());
        }
    }

    public static function update($id, $code, $discountType, $discountValue, $startDate, $endDate, $minOrderValue, $isActive){
        try {
            DB::query(
                "UPDATE coupons 
                SET code = ?, discount_type = ?, discount_value = ?, start_date = ?, end_date = ?, min_order_value = ?, is_active = ?
                WHERE id = ?",
                [$code, $discountType, $discountValue, $startDate, $endDate, $minOrderValue, $isActive, $id]
            );
        } catch (Exception $e) {
            throw new Exception("Failed to update coupon: " . $e->getMessage());
        }
    }

    public static function delete($id){
        try {
            DB::query("DELETE FROM coupons WHERE id = ?", [$id]);
        } catch (Exception $e) {
            throw new Exception("Failed to delete coupon: " . $e->getMessage());
        }
    }

    public static function validateCoupon($code, $orderTotal){
        try {
            $coupon = self::findByCode($code);
            
            if (!$coupon) {
                return ['valid' => false, 'message' => 'Invalid coupon code'];
            }

            // Check if coupon is active
            if (!$coupon['is_active']) {
                return ['valid' => false, 'message' => 'This coupon is not active'];
            }

            // Check if coupon has started
            $currentDate = date('Y-m-d');
            if ($coupon['start_date'] && $currentDate < $coupon['start_date']) {
                return ['valid' => false, 'message' => 'This coupon is not yet valid'];
            }

            // Check if coupon has expired
            if ($coupon['end_date'] && $currentDate > $coupon['end_date']) {
                return ['valid' => false, 'message' => 'This coupon has expired'];
            }

            // Check minimum order value
            if ($coupon['min_order_value'] && $orderTotal < $coupon['min_order_value']) {
                return [
                    'valid' => false, 
                    'message' => 'Minimum order value of $' . number_format($coupon['min_order_value'], 2) . ' required'
                ];
            }

            // Calculate discount
            $discount = 0;
            if ($coupon['discount_type'] === 'percentage') {
                $discount = ($orderTotal * $coupon['discount_value']) / 100;
            } else if ($coupon['discount_type'] === 'fixed') {
                $discount = $coupon['discount_value'];
            }

            return [
                'valid' => true,
                'discount' => $discount,
                'discount_type' => $coupon['discount_type'],
                'discount_value' => $coupon['discount_value'],
                'message' => 'Coupon applied successfully'
            ];

        } catch (Exception $e) {
            return ['valid' => false, 'message' => 'Error validating coupon'];
        }
    }
}
