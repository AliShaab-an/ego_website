<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Cart{

        public static function getCart($userId = null, $sessionId= null){
            try{
                if($userId){
                    $stmt = DB::query("SELECT * FROM cart WHERE user_id = ?",[$userId]);
                }else{
                    $stmt = DB::query("SELECT * FROM cart WHERE session_id = ?",[$sessionId]);
                }

                $cart = $stmt->fetch(PDO::FETCH_ASSOC);
                return $cart ?: null;

            }catch(Exception $e){
                error_log($e->getMessage());
                return null;
            }
        }

        public static function createCart($userId = null, $sessionId = null){
            try{
                DB::query("INSERT INTO cart (user_id, session_id, created_at) VALUES (?, ?, NOW())", [$userId, $sessionId]);
                return DB::getConnection()->lastInsertId();

            }catch(Exception $e){
                error_log($e->getMessage());
            }
        }

        public static function addItem($cartId, $productId, $quantity = 1, $price, $variantId = null, $imageId = null){
            try{
                $stmt = DB::query("
                SELECT * FROM cart_item 
                WHERE cart_id = ? AND product_id = ? AND (variant_id = ? OR (variant_id IS NULL AND ? IS NULL))
                ", [$cartId, $productId, $variantId, $variantId]);

                $item= $stmt->fetch(PDO::FETCH_ASSOC);

                if($item){
                    DB::query("UPDATE cart_item SET quantity = quantity + ?, updated_at = NOW() WHERE item_id = ?", [$quantity, $item[0]['item_id']]);
                }else{
                    DB::query("
                    INSERT INTO cart_item (cart_id, product_id, variant_id, image_id, quantity, price, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                    ", [$cartId, $productId, $variantId, $imageId, $quantity, $price]);
                }

            }catch(Exception $e){
                error_log("CartModel addItem error: " . $e->getMessage(), 3, __DIR__ . "/logs/debug.log");
            }
        }

        public static function getItems($cartId){
            try {
                $items = DB::query("SELECT * FROM cart_item WHERE cart_id = ?", [$cartId]);
                return $items->fetchAll(PDO::FETCH_ASSOC);
            } catch(Exception $e){
                error_log($e->getMessage());
                return [];
            }
        }

        public static function updateItemQuantity($itemId, $quantity){
            try {
                return DB::query("UPDATE cart_item SET quantity = ?, updated_at = NOW() WHERE item_id = ?", [$quantity, $itemId]);
            } catch(Exception $e){
                error_log($e->getMessage());
                return false;
            }
        }

        public static function removeItem($itemId){
            try {
                return DB::query("DELETE FROM cart_item WHERE item_id = ?", [$itemId]);
            }catch(Exception $e){
                error_log($e->getMessage());
                return false;
            }
        }

        public static function clearCart($cartId){
            try {
                DB::query("DELETE FROM cart_item WHERE cart_id = ?", [$cartId]);
                DB::query("DELETE FROM cart WHERE cart_id = ?", [$cartId]);
            }catch(Exception $e){
                error_log($e->getMessage());
            }
        }

        public static function addOrUpdateItem($cartId, $productId, $quantity, $price, $variantId = null, $imageId = null) {
            try {
                // Check if item with same product + variant exists
                $sql = "SELECT * FROM cart_item 
                        WHERE cart_id = :cart_id 
                        AND product_id = :product_id 
                        AND variant_id = :variant_id
                        LIMIT 1";

                $stmt = DB::query($sql,[':cart_id' => $cartId,
                ':product_id' => $productId,
                ':variant_id' => $variantId,
                ]);

                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Update quantity
                    $newQty = $existing['quantity'] + $quantity;
                    
                    DB::query("UPDATE cart_item SET quantity = :quantity WHERE id = :id", [':quantity' => $newQty, ':id' => $existing['id']]);
                } else {
                    // Insert new item
                    DB::query("INSERT INTO cart_item (cart_id, product_id, variant_id, image_id, quantity, price, created_at)
                    VALUES (:cart_id, :product_id, :variant_id, :image_id, :quantity, :price, NOW())", [
                    ':cart_id'    => $cartId,
                    ':product_id' => $productId,
                    ':variant_id' => $variantId,
                    ':image_id'   => $imageId,
                    ':quantity'   => $quantity,
                    ':price'      => $price
            ]);
                }
            } catch (Exception $e) {
                    error_log("addOrUpdateItem error: " . $e->getMessage(), 3, __DIR__ . "/logs/debug.log");
                    throw $e;
            }
        }

        public static function getCartCount($cartId) {
            try {
                $sql = DB::query("SELECT SUM(quantity) as total FROM cart_item WHERE cart_id = ?", [$cartId]);
                $result = $sql->fetch(PDO::FETCH_ASSOC);
                return $result['total'] ?? 0;
            }catch(Exception $e) {
                error_log("Cart getCartCount error: " . $e->getMessage(), 3, __DIR__ . "/../../logs.debug.log");
                return 0;
            }
        }
    }

    