<?php 

    require_once __DIR__ . '/../core/DB.php';

    class ProductImages{

        public static function addImage($productId,$imagePath, $isMain = 0){

            if(empty($productId) || empty($imagePath)){
                return false;
            }

            $count = DB::query("SELECT COUNT(*) FROM product_images WHERE product_id = ?", [$productId])->fetchColumn();

            if($count == 0 ){
                $isMain = 1;
            }

            DB::query("INSERT INTO product_images (product_id, image_path, is_main) VALUES (?, ?, ?)",[
                $productId,
                $imagePath,
                $isMain,
            ]);

            return DB::getConnection()->lastInsertId();
        }

        public static function getImages($productId) {
            return DB::query("SELECT * FROM product_images WHERE product_id = ?",       [$productId])->fetchAll();
        }

        public static function getById($id){
            return DB::query("SELECT * FROM product_images WHERE id = ?", [$id])->fetch();
        }

        public static function deleteImage($id) {
            DB::query("DELETE FROM product_images WHERE id = ?", [$id]);
        }
    }