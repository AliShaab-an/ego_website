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
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return null;
            }
        }

        public static function getOrCreateCart($userId){
            try{
                $stmt = DB::query("SELECT cart_id FROM cart WHERE user_id = ?", [$userId]);
                $cart = $stmt->fetch(PDO::FETCH_ASSOC);
                if($cart){
                    return $cart['cart_id'];
                }else{
                    DB::query("INSERT INTO cart (user_id) VALUES (?)", [$userId]);
                    return DB::getConnection()->lastInsertId();
                }  
            }catch(PDOException $e){
                throw new Exception("Failed to get or create cart: " . $e->getMessage());
            }
        }

        public static function addItemForUser($userId, $productId, $size, $color, $quantity = 1) {
            try {
                
                $cartId = self::getOrCreateCart($userId);

                $variantQuery = DB::query("
                    SELECT v.id AS variant_id, 
                        CASE 
                            WHEN v.price IS NOT NULL AND v.price > 0 THEN
                                CASE 
                                    WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                                        v.price * (1 - pd.discount_percentage / 100)
                                    ELSE v.price 
                                END
                            ELSE
                                CASE 
                                    WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                                        p.base_price * (1 - pd.discount_percentage / 100)
                                    ELSE p.base_price 
                                END
                        END AS price
                    FROM product_variants v
                    INNER JOIN products p ON p.id = v.product_id
                    LEFT JOIN colors c ON v.color_id = c.id
                    LEFT JOIN sizes s ON v.size_id = s.id
                    LEFT JOIN product_discounts pd ON p.id = pd.product_id
                    WHERE v.product_id = ?
                    AND (c.name = ? OR ? IS NULL)
                    AND (s.name = ? OR ? IS NULL)
                    LIMIT 1
                ", [$productId, $color, $color, $size, $size]);

                $variantData = $variantQuery->fetch(PDO::FETCH_ASSOC);
                
                if (empty($variantData)) {
                    return false; 
                }

                $variantId = $variantData['variant_id'];
                $price = $variantData['price'];

                // Check if this variant already exists in cart
                $stmt = DB::query("
                    SELECT item_id, quantity 
                    FROM cart_item 
                    WHERE cart_id = ? AND variant_id = ?
                ", [$cartId, $variantId]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Replace quantity instead of adding to it
                    DB::query("UPDATE cart_item SET quantity = ? WHERE item_id = ?", [$quantity, $existing['item_id']]);
                } else {
                    // 5️⃣ Insert new cart item
                    DB::query("
                        INSERT INTO cart_item (cart_id, product_id, variant_id, quantity, price)
                        VALUES (?, ?, ?, ?, ?)
                    ", [$cartId, $productId, $variantId, $quantity, $price]);
                }

                return true;

            } catch (PDOException $e) {
                throw new Exception("Failed to add item to cart: " . $e->getMessage());
            }
        }


        public static function getCartCount($userId) {
            $stmt = DB::query("
                SELECT SUM(ci.quantity) AS total 
                FROM cart_item ci
                INNER JOIN cart c ON c.cart_id = ci.cart_id
                WHERE c.user_id = ?
            ", [$userId]);
            $count = $stmt->fetch(PDO::FETCH_ASSOC);

            return $count['total'] ?? 0;
        }

        public static function getCartItemsWithDetails($cartId) {
            try {
                $stmt = DB::query("
                    SELECT 
                        ci.item_id,
                        ci.product_id,
                        ci.quantity,
                        ci.price,
                        p.name,
                        p.description,
                        c.name AS color,
                        s.name AS size,
                        pi.image_path AS image
                    FROM cart_item ci
                    JOIN products p ON ci.product_id = p.id
                    LEFT JOIN product_variants pv ON ci.variant_id = pv.id
                    LEFT JOIN colors c ON pv.color_id = c.id
                    LEFT JOIN sizes s ON pv.size_id = s.id
                    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                    WHERE ci.cart_id = ?
                ", [$cartId]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(Exception $e) {
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return [];
            }
        }

        public static function getCartTotal($cartId) {
            try {
                $stmt = DB::query("
                    SELECT SUM(ci.quantity * ci.price) AS total
                    FROM cart_item ci
                    WHERE ci.cart_id = ?
                ", [$cartId]);
                $total = $stmt->fetch(PDO::FETCH_ASSOC);

                return $total['total'] ?? 0;
            } catch(Exception $e) {
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return 0;
            }
        }

        public static function updateItemQuantityForUser($userId, $productId, $size, $color, $quantity) {
            try {
                $cartId = self::getOrCreateCart($userId);

                // Find the variant
                $variantQuery = DB::query("
                    SELECT v.id AS variant_id
                    FROM product_variants v
                    LEFT JOIN colors c ON v.color_id = c.id
                    LEFT JOIN sizes s ON v.size_id = s.id
                    WHERE v.product_id = ?
                    AND (c.name = ? OR ? IS NULL)
                    AND (s.name = ? OR ? IS NULL)
                    LIMIT 1
                ", [$productId, $color, $color, $size, $size]);

                $variantData = $variantQuery->fetchAll(PDO::FETCH_ASSOC);
                if (empty($variantData)) {
                    return false;
                }

                $variantId = $variantData[0]['variant_id'];

                if ($quantity <= 0) {
                    // Remove item if quantity is 0 or less
                    DB::query("
                        DELETE FROM cart_item 
                        WHERE cart_id = ? AND variant_id = ?
                    ", [$cartId, $variantId]);
                } else {
                    // Update quantity
                    DB::query("
                        UPDATE cart_item 
                        SET quantity = ? 
                        WHERE cart_id = ? AND variant_id = ?
                    ", [$quantity, $cartId, $variantId]);
                }

                return true;

            } catch (Exception $e) {
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return false;
            }
        }

        public static function removeItemForUser($userId, $productId, $size, $color) {
            try {
                $cartId = self::getOrCreateCart($userId);

                // Find the variant
                $variantQuery = DB::query("
                    SELECT v.id AS variant_id
                    FROM product_variants v
                    LEFT JOIN colors c ON v.color_id = c.id
                    LEFT JOIN sizes s ON v.size_id = s.id
                    WHERE v.product_id = ?
                    AND (c.name = ? OR ? IS NULL)
                    AND (s.name = ? OR ? IS NULL)
                    LIMIT 1
                ", [$productId, $color, $color, $size, $size]);

                $variantData = $variantQuery->fetchAll(PDO::FETCH_ASSOC);
                if (empty($variantData)) {
                    return false;
                }

                $variantId = $variantData[0]['variant_id'];

                DB::query("
                    DELETE FROM cart_item 
                    WHERE cart_id = ? AND variant_id = ?
                ", [$cartId, $variantId]);

                return true;

            } catch (Exception $e) {
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return false;
            }
        }


        public static function getItems($cartId){
            try {
                $items = DB::query("SELECT * FROM cart_item WHERE cart_id = ?", [$cartId]);
                return $items->fetchAll(PDO::FETCH_ASSOC);
            } catch(Exception $e){
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return [];
            }
        }

        public static function updateItemQuantity($itemId, $quantity){
            try {
                return DB::query("UPDATE cart_item SET quantity = ?, updated_at = NOW() WHERE item_id = ?", [$quantity, $itemId]);
            } catch(Exception $e){
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return false;
            }
        }

        public static function removeItem($itemId){
            try {
                return DB::query("DELETE FROM cart_item WHERE item_id = ?", [$itemId]);
            }catch(Exception $e){
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                return false;
            }
        }

        public static function clearCart($cartId){
            try {
                DB::query("DELETE FROM cart_item WHERE cart_id = ?", [$cartId]);
                DB::query("DELETE FROM cart WHERE cart_id = ?", [$cartId]);
            }catch(Exception $e){
                file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
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
                    file_put_contents(__DIR__ . '/../../logs/model.log', $e->getMessage() . "\n", FILE_APPEND);
                    throw $e;
            }
        }

    }

    