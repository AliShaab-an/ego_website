<?php 

    require_once __DIR__ . '/../core/DB.php';

    class ProductImages{

        public static function addImage($productId,$imagePath, $isMain = 0){
            file_put_contents(__DIR__ . '/../../logs/debug.log', "Inserting image: product_id={$productId}, path={$imagePath}, is_main={$isMain}\n", FILE_APPEND);

            if(empty($productId) || empty($imagePath)){
                error_log("addImage() skipped. ProductId: $productId, Path: $imagePath");
                return false;
            }

            try {
                $count = DB::query("SELECT COUNT(*) FROM product_images WHERE product_id = ?", [$productId])
                        ->fetchColumn();

                if ($count == 0) {
                    $isMain = 1;
                }

                DB::query("INSERT INTO product_images (product_id, image_path, is_main) VALUES (?, ?, ?)", [
                    $productId,
                    $imagePath,
                    $isMain,
                ]);

                return DB::getConnection()->lastInsertId();
            } catch (Exception $e) {
                error_log("Image insert failed: " . $e->getMessage());
                return false;
            }
            
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