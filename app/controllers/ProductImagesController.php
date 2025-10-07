<?php 
    require_once __DIR__ . '/../config/path.php';
    require_once CORE . 'Logger.php';
    require_once __DIR__ . '/../models/ProductImages.php';


    class ProductImagesController{

        public function uploadImages(
            $productId,
            ?int $variantId,
            ?int $colorId,
            array $altText,
            array $displayOrders,
            array $files
            ){
            
            Logger::controller("uploadImages() called for product_id={$productId}, variant_id={$variantId}, color_id={$colorId}");

            $uploadDir = __DIR__ . '/../../public/admin/uploads/';
            
            // Allowed file types
            $allowedTypes = ['image/jpeg','image/png','image/webp'];
            $maxSize = 2 * 1024 * 1024; //2MB limit
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0777, true);
                Logger::controller("Created upload directory at {$uploadDir}");
            }

            try {

                if (empty($files['name']) || !is_array($files['name'])) {
                    Logger::error("ProductController::uploadImages", "No files provided");
                    return ['status' => 'error', 'message' => 'No files provided'];
                }

                 // Check if there is already a main image for this color
                $hasMain = ProductImages::hasMainImage($productId, $colorId);
                $uploaded = [];

                foreach ($files['name'] as $key => $name) {
                    $tmpName = $files['tmp_name'][$key];
                    $error = $files['error'][$key];
                    $size = $files['size'][$key];

                    if ($error !== UPLOAD_ERR_OK || !$tmpName || !file_exists($tmpName))continue; 

                    // Validate file type
                    $type = mime_content_type($tmpName);
                    if (!in_array($type, $allowedTypes)) continue;

                    // Validate file size
                    if ($size > $maxSize) continue;

                    // Generate unique safe name
                    $safeName = uniqid() . "_" . basename($name);
                    $target = $uploadDir . $safeName;

                    if (!move_uploaded_file($tmpName, $target)) continue;

                    $relativePath = "admin/uploads/" . $safeName;

                    // Get optional alt text and display order
                    $altText = $altTexts[$key] ?? null;
                    $displayOrder = $displayOrders[$key] ?? $key;

                    $isMain = $hasMain ? 0 : 1;
                    if ($isMain) $hasMain = true;
                    
                    // Save image to DB with metadata
                    $result = ProductImages::addImage([
                        'product_id'   => $productId,
                        'variant_id'   => $variantId,
                        'color_id'     => $colorId,
                        'image_path'   => $relativePath,
                        'alt_text'     => $altText,
                        'display_order'=> $displayOrder,
                        'is_main' => $isMain,
                    ]);

                    if ($result) {
                        $uploaded[] = $relativePath;
                        Logger::model("Image saved: {$relativePath}, main={$isMain}, order={$displayOrder}");
                    }
                }

                if (!empty($uploaded)) {
                    return ['status' => 'success', 'message' => 'Images uploaded successfully', 'uploaded' => $uploaded];
                }

                return ['status' => 'error', 'message' => 'No valid images were uploaded'];
            } catch (Exception $e) {
                Logger::error("ProductController::uploadImages", $e->getMessage());
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function deleteImage($id){
            $image = ProductImages::getById($id);
            if($image){
                unlink(__DIR__ . '/../../public/' . $image['image_path']);
                ProductImages::deleteImage($id);
            }
        }

        public function getMainImage($productId){
            $image = ProductImages::getMainByProduct($productId);
            return $image ?: null;
        }

        // public function getVariantImage($variantId) {
        //     $image = ProductImages::getByVariant($variantId);
        //     return $image ?: null;
        // }

    // ✅ Decide which one to use (variant image preferred)
        // public function getImageForCart($productId, $variantId = null) {
        //     if ($variantId) {
        //         $variantImage = $this->getVariantImage($variantId);
        //         if ($variantImage) return $variantImage;
        //     }
        //     return $this->getMainImage($productId);
        // }
    }