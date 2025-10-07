<?php 

    require_once __DIR__ . '/../core/DB.php';


    class ProductVariant{

        public static function createProductVariant($product_id,$data){
            try{
                $color_id = isset($data['color_id']) ? intval($data['color_id'])  :  null;
                $size_id  = isset($data['size_id']) ? intval($data['size_id']) : null;
                $price = isset($data['price']) && $data['price'] !== '' ? $data['price'] : null;
                $quantity = isset($data['quantity']) ? intval($data['quantity']) : 0;

                if (!$color_id || !$size_id) {
                    throw new Exception("Color or Size ID missing");
                }

                DB::query("INSERT INTO product_variants (product_id,color_id,size_id,price,quantity) VALUES (?,?,?,?,?)", [
                    $product_id,
                    $color_id,
                    $size_id,
                    $price,
                    $quantity,
                ]);
                return DB::getConnection()->lastInsertId();
            }catch(Exception $e){
                error_log("Variant insert failed: " . $e->getMessage());
                return false;
            } 
        }
    }
