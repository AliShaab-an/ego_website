<?php 

    require_once __DIR__ . '/../core/DB.php';


    class ProductVariant{

        public static function createProductVariant($productId,$data){
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
                throw $e; 
            } 
        }
    }
