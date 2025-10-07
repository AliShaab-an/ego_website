<?php 

    require_once __DIR__ . '/../core/DB.php';

    class ProductImages{

        public static function addImage($data){
            try {
                $product_id    = $data['product_id'] ?? null;
                $variant_id    = $data['variant_id'] ?? null;
                $color_id      = $data['color_id'] ?? null;
                $image_path    = $data['image_path'] ?? null;
                $alt_text      = $data['alt_text'] ?? null;
                $display_order = $data['display_order'] ?? 0;
                $is_main       = $data['is_main'] ?? 0;

                file_put_contents(
                    __DIR__ . '/../../logs/debug.log',
                    "Inserting image: product_id={$product_id}, variant_id={$variant_id}, color_id={$color_id}, path={$image_path}, order={$display_order}, is_main={$is_main}\n",
                    FILE_APPEND
                );

                // Validate
                if (empty($product_id) || empty($image_path)) {
                    error_log("addImage() skipped. Missing product_id or image_path");
                    return false;
                }

                // Automatically set the first image as main if none exists
                $count = DB::query("SELECT COUNT(*) FROM product_images WHERE product_id = ?", [$product_id])
                            ->fetchColumn();

                if ($count == 0) {
                    $is_main = 1;
                }

                DB::query("
                    INSERT INTO product_images 
                    (product_id, variant_id, color_id, image_path,is_main,alt_text, display_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ", [
                    $product_id,
                    $variant_id,
                    $color_id,
                    $image_path,
                    $is_main,
                    $alt_text,
                    $display_order,
                ]);

                return DB::getConnection()->lastInsertId();
            }catch (Exception $e) {
            error_log("Image insert failed: " . $e->getMessage());
            return false;
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