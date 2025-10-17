<?php 

    require_once __DIR__ . '/../core/DB.php';


    class ProductVariant{

        public static function create($productId,$data){
            try{
                $color_id = isset($data['color_id']) ? intval($data['color_id']) : null;
                $size_id  = isset($data['size_id']) ? intval($data['size_id']) : null;
                $price    = isset($data['price']) && $data['price'] !== '' ? floatval($data['price']) : 0;
                $quantity = isset($data['quantity']) ? intval($data['quantity']) : 0;

                if (empty($color_id) || empty($size_id)) {
                    throw new Exception("Color or Size ID missing when creating variant.");
                }

                $sql = "INSERT INTO product_variants 
                (product_id, color_id, size_id, price, quantity) 
                VALUES (?, ?, ?, ?, ?)";
        
                DB::query($sql, [
                    $productId,
                    $color_id,
                    $size_id,
                    $price,
                    $quantity
                ]);

                file_put_contents(__DIR__ . '/../../logs/error.log',
                    "Inserted Variant: Product #$productId | Color $color_id | Size $size_id | Price $price | Qty $quantity\n",
                    FILE_APPEND
                );
                return DB::getConnection()->lastInsertId();
            }catch(Exception $e){
                file_put_contents(__DIR__ . '/../../logs/error.log',
                    "Variant insert failed: " . $e->getMessage() . "\n",
                    FILE_APPEND
                );
                return ['status' => 'error', 'message' => 'Failed to add variant: ' . $e->getMessage()]; 
            } 
        }

        // public static function create($productId, $data){
        //     try {
        //         $sql = "INSERT INTO product_variants (product_id, color_id, size_id, price, quantity, is_active, created_at, updated_at)
        //                 VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())";
        //         DB::query($sql, [
        //             intval($productId),
        //             intval($data['color_id'] ?? 0),
        //             intval($data['size_id'] ?? 0),
        //             floatval($data['price'] ?? 0),
        //             intval($data['quantity'] ?? 0)
        //         ]);
        //         return DB::getConnection()->lastInsertId();
        //     } catch (Exception $e) {
        //         throw new Exception("Failed to create variant: " . $e->getMessage());
        //     }
        // }



        public static function getById($productId) {
            try{
                $stmt = DB::query("
                SELECT v.*, c.name AS color_name, s.name AS size_name
                FROM product_variants v
                LEFT JOIN colors c ON v.color_id = c.id
                LEFT JOIN sizes s ON v.size_id = s.id
                WHERE v.product_id = ?", [$productId]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                throw new Exception("Failed to fetch variants: " . $e->getMessage());
            }
        }

        public static function updateStatusByProduct($productId, $isActive) {
            try{
                DB::query("UPDATE product_variants SET is_active = ? WHERE product_id = ?", [$isActive, $productId]);
            } catch(Exception $e){
                throw new Exception("Failed to update variants status: " . $e->getMessage());
            }
        }

        public static function update($id, $data){
            try {
                $sql = "UPDATE product_variants
                        SET color_id = ?, size_id = ?, price = ?, quantity = ?, updated_at = NOW()
                        WHERE id = ?";
                DB::query($sql, [
                    intval($data['color_id'] ?? 0),
                    intval($data['size_id'] ?? 0),
                    floatval($data['price'] ?? 0),
                    intval($data['quantity'] ?? 0),
                    intval($id)
                ]);
                return true;
            } catch (Exception $e) {
                throw new Exception("Failed to update variant: " . $e->getMessage());
            }
        }

        public static function deleteByProduct($productId){
            DB::query("DELETE FROM product_variants WHERE product_id = ?", [$productId]);
        }
    }
