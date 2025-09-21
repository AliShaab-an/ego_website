<?php 

    require_once __DIR__ . '/../models/ProductVariant.php';
    class ProductVariantsController{

        public function addVariant($productId, $variantData){
            $color_id = intval($variantData['color_id'] ?? 0);
            $size_id = intval($variantData['size_id'] ?? 0);
            $quantity = intval($variantData['quantity'] ?? 0);

            if ($color_id <= 0 || $size_id <= 0) {
                return ['status' => 'error', 'message' => "Invalid variant data"];
            }

            if ($quantity < 0) {
                return ['status' => 'error', 'message' => "Quantity cannot be negative"];
            }

            $variantId = ProductVariant::createProductVariant($productId, [
                'color_id' => $color_id,
                'size_id' => $size_id,
                'quantity' => $quantity,
                
                ]);

            return ['status' => 'success','id' => $variantId, 'message' => 'Variant added'];
            
        }
    }