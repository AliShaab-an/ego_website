<?php 

    require_once __DIR__ . '/../core/DB.php';


    class ProductVariant{


        public static function createProductVariant($product_id,$data){
            DB::query("INSERT INTO product_variants (product_id,color_id,size_id,price,quantity) VALUES (?,?,?,?,?)", [
                $product_id,
                $data['color_id'],
                $data['size_id'],
                $data['price'],
                $data['quantity'],
            ]);
            return DB::getConnection()->lastInsertId();
        }
    }
