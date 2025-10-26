<?php

require_once __DIR__ . '/../core/DB.php';

class ProductDiscount {
    

    public static function create($productId, $discountPercentage, $isActive = 0) {
        try {
            // Validate discount percentage
            if ($discountPercentage < 0 || $discountPercentage > 100) {
                throw new Exception("Discount percentage must be between 0 and 100");
            }
            
            $sql = "INSERT INTO product_discounts (product_id, discount_percentage, is_active) 
                    VALUES (?, ?, ?)";
            
            DB::query($sql, [
                intval($productId),
                floatval($discountPercentage),
                intval($isActive)
            ]);
            
            return DB::getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Failed to create product discount: " . $e->getMessage());
        }
    }
    
    
    public static function update($productId, $discountPercentage, $isActive = 0) {
        try {
            // Validate discount percentage
            if ($discountPercentage < 0 || $discountPercentage > 100) {
                throw new Exception("Discount percentage must be between 0 and 100");
            }
            
            // Check if discount exists
            $stmt = DB::query("SELECT id FROM product_discounts WHERE product_id = ?", [$productId]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing) {
                // Update existing discount
                $sql = "UPDATE product_discounts 
                        SET discount_percentage = ?, is_active = ? 
                        WHERE product_id = ?";
                DB::query($sql, [
                    floatval($discountPercentage),
                    intval($isActive),
                    intval($productId)
                ]);
            } else {
                // Create new discount
                self::create($productId, $discountPercentage, $isActive);
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to update product discount: " . $e->getMessage());
        }
    }
    
    /**
     * Get discount by product ID
     */
    public static function getByProductId($productId) {
        try {
            $stmt = DB::query("SELECT * FROM product_discounts WHERE product_id = ?", [$productId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Failed to get product discount: " . $e->getMessage());
        }
    }
    
    /**
     * Delete discount by product ID
     */
    public static function deleteByProductId($productId) {
        try {
            DB::query("DELETE FROM product_discounts WHERE product_id = ?", [$productId]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to delete product discount: " . $e->getMessage());
        }
    }
    
    /**
     * Toggle discount active status
     */
    public static function toggleStatus($productId, $isActive) {
        try {
            DB::query("UPDATE product_discounts SET is_active = ? WHERE product_id = ?", [
                intval($isActive),
                intval($productId)
            ]);
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to toggle discount status: " . $e->getMessage());
        }
    }
}