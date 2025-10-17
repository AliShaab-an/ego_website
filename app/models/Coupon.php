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
}
