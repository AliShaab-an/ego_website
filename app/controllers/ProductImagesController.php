<?php 
    require_once __DIR__ . '/../config/path.php';
    require_once CORE . 'Logger.php';
    require_once __DIR__ . '/../models/ProductImages.php';


    class ProductImagesController{

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