<?php 

    require_once __DIR__ . '/../core/DB.php';


    class ProductVariant{


        public static function createProductVariant($product_id,$data){
            try{
                $color_id = $data['color_id'] ?? null;
                $size_id  = $data['size_id'] ?? null;
                $price = isset($data['price']) && $data['price'] !== '' ? $data['price'] : null;
                $quantity = $data['quantity'] ?? 0;

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
