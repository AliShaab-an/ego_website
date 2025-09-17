<?php 

    require_once __DIR__ . '/../models/ProductVariant.php.php';
    class ProductVariantsController{

        public function addVariant($productId, $variantData){

            if(empty($variantData['color_id']) || empty($variantData['size_id'])){
                return ['status' => 'error', 'message' => "Invalid variant data"];
            }

            ProductVariant::createProductVariant($productId,[
                'color_id' => $variantData['color_id'],
                'size_id' => $variantData['size_id'],
                'price' => $variantData['price'],
                'quantity' => $variantData['quantity'],
            ]);

            return ['status' => 'success', 'message' => 'Variant added'];
            
        }
    }