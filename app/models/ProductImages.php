<?php 

    require_once __DIR__ . '/../core/DB.php';

    class ProductImages{

        public static function addImage($data){
            try {

                if (empty($data['product_id']) || empty($data['image_path'])) {
                    error_log("❌ ProductImages::addImage missing product_id or image_path\n" . print_r($data, true));
                    return false;
                }
                

                $sql = "INSERT INTO product_images 
                (product_id, variant_id, color_id, image_path, is_main, display_order) 
                VALUES (?, ?, ?, ?, ?, ?)";

                DB::query($sql, [
                    $data['product_id'],
                    $data['variant_id'] ?? null,
                    $data['color_id'] ?? null,
                    $data['image_path'],
                    $data['is_main'] ?? 0,
                    $data['display_order'] ?? 1
                ]);

                $insertId = DB::getConnection()->lastInsertId();

                file_put_contents(
                    __DIR__ . '/../../logs/image_debug.log',
                    "✅ Inserted addImage\n",
                    FILE_APPEND
                );

                return $insertId;

            }catch (Exception $e) {
            file_put_contents(
            __DIR__ . '/../../logs/image_error.log',"❌ Image insert failed: " . $e->getMessage() . "\n",
            FILE_APPEND
            );
            throw new Exception("Failed to add images: " . $e->getMessage());;
            }
        }

        public static function hasMainImage(int $productId, ?int $colorId): bool {
            $stmt = DB::query(
                "SELECT COUNT(*) FROM product_images WHERE product_id = ? AND color_id = ? AND is_main = 1",
                [$productId, $colorId]
            );
            return $stmt->fetchColumn() > 0;
        }


        public static function getMainByProduct($productId){
            try{
                $stmt = DB::query("SELECT * FROM product_images 
                WHERE product_id = ? AND is_main = 1 
                LIMIT 1",
                [$productId]);

                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                file_put_contents(__DIR__ . '/../../logs/debug.log', "GetMainByProduct/n", FILE_APPEND);
            }
            
        }

        public static function deleteImage($id) {
            DB::query("DELETE FROM product_images WHERE id = ?", [$id]);
        }


        public static function getById($productId) {
            try{
                $stmt = DB::query("SELECT * FROM product_images WHERE product_id = ?", [$productId]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                throw new Exception("Failed to fetch images: " . $e->getMessage());
            }
        }

        public static function getByVariant($variantId){
            $stmt = DB::query("
                SELECT * FROM product_images 
                WHERE variant_id = ?
                ORDER BY display_order ASC, id ASC
            ", [$variantId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function deleteById($id){
            $stmt = DB::query("SELECT image_path FROM product_images WHERE id = ?", [$id]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($img && !empty($img['image_path'])) {
                $file = __DIR__ . '/../../public/admin/uploads/' . $img['image_path'];
                if (file_exists($file)) unlink($file);
            }

            DB::query("DELETE FROM product_images WHERE id = ?", [$id]);
        }

        public static function deleteByProduct($productId){
            $images = self::getById($productId);
            foreach ($images as $img) {
                self::deleteById($img['id']);
            }
        }
    }