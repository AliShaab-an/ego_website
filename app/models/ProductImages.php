<?php 

    require_once __DIR__ . '/../core/DB.php';

    class ProductImages{

        public static function addImage($data){
            try {
                $productId    = $data['product_id'] ?? null;
                $variant_id    = $data['variant_id'] ?? null;
                $color_id      = $data['color_id'] ?? null;
                $file          = $data['file'] ?? null;
                $alt_text      = $data['alt_text'] ?? null;
                $display_order = isset($data['display_order']) ? intval($data['display_order']) : 0;
                $is_main       = isset($data['is_main']) ? intval($data['is_main']) : 0;

                if (empty($productId) || empty($file) || !isset($file['tmp_name'])) {
                    throw new Exception("Missing product_id or file in addImage()");
                }

                
                $uploadDir = __DIR__ . '/../../public/admin/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', basename($file['name']));
                $fileName = uniqid("p{$productId}_") . "_" . $safeName;
                $targetPath = $uploadDir . $fileName;
                $dbPath = "admin/uploads/" . $fileName; // relative path for DB

                
                if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                    throw new Exception("Failed to upload image: {$file['name']}");
                }

                $count = DB::query("SELECT COUNT(*) FROM product_images WHERE product_id = ?", [$productId])->fetchColumn();
                if ($count == 0) $is_main = 1;

                DB::query("
                    INSERT INTO product_images 
                    (product_id, variant_id, color_id, image_path, is_main, alt_text, display_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ", [
                    $productId,
                    $variant_id,
                    $color_id,
                    $dbPath,
                    $is_main,
                    $alt_text,
                    $display_order
                ]);

                $insertId = DB::getConnection()->lastInsertId();

                file_put_contents(
                    __DIR__ . '/../../logs/image_debug.log',
                    "✅ Inserted image: product_id=$productId | variant_id=$variant_id | color_id=$color_id | file=$dbPath\n",
                    FILE_APPEND
                );

                return $insertId;

            }catch (Exception $e) {
            file_put_contents(
            __DIR__ . '/../../logs/image_error.log',"❌ Image insert failed: " . $e->getMessage() . "\n",
            FILE_APPEND
            );
            throw $e;
            }
        }

        public static function hasMainImage(int $productId, ?int $colorId): bool {
            $stmt = DB::query(
                "SELECT COUNT(*) FROM product_images WHERE product_id = ? AND color_id = ? AND is_main = 1",
                [$productId, $colorId]
            );
            return $stmt->fetchColumn() > 0;
        }


        public static function getById($id){
            return DB::query("SELECT * FROM product_images WHERE id = ? AND is_main = 1", [$id])->fetch();
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
    }