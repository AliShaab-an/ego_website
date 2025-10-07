<?php 
    require_once __DIR__ . '/../config/path.php';
    require_once CORE . 'Logger.php';
    require_once __DIR__ . '/../models/ProductVariant.php';
    class ProductVariantsController{

        public function addProductVariants($productId, $variantData){

            Logger::controller("addProductVariants() called for product_id={$productId}");
            try{
                // --- Validate product ID ---
                if (empty($productId) || $productId <= 0) {
                    Logger::error("ProductController::addProductVariants", "Invalid product ID: $productId");
                    return ['status' => 'error', 'message' => 'Invalid product ID'];
                }
                 // --- Validate color ---
                $color_id = intval($variantData['color_id'] ?? 0);
                if ($color_id <= 0) {
                    Logger::error("ProductController::addProductVariants", "Invalid color_id in variantData");
                    return ['status' => 'error', 'message' => 'Invalid color ID'];
                }

                if (empty($variantData['sizes']) || !is_array($variantData['sizes'])) {
                    Logger::error("ProductController::addProductVariants", "Missing or invalid sizes array for color_id={$color_id}");
                    return ['status' => 'error', 'message' => 'Variant must have at least one size'];
                }

                $errors = [];
                $successCount = 0;

                 // --- Loop through each size entry ---
                foreach ($variantData['sizes'] as $size) {
                    $size_id = intval($size['size_id'] ?? 0);
                    $price = floatval($size['price'] ?? 0);
                    $quantity = intval($size['quantity'] ?? 0);

                    // Validate size data
                    if ($size_id <= 0) {
                        $errors[] = "Invalid size ID for color $color_id";
                        Logger::error("ProductController::addProductVariants", "Invalid size_id for color_id={$color_id}");
                        continue;
                    }

                    if ($quantity < 0) {
                        $errors[] = "Quantity cannot be negative for size $size_id";
                        Logger::error("ProductController::addProductVariants", "Negative quantity for size_id={$size_id}");
                        continue;
                    }

                    // --- Create variant record ---
                    $variantId = ProductVariant::createProductVariant($productId, [
                        'color_id' => $color_id,
                        'size_id' => $size_id,
                        'price' => $price,
                        'quantity' => $quantity,
                    ]);

                    if ($variantId) {
                        $successCount++;
                        Logger::model("Variant created: variant_id={$variantId}, product_id={$productId}, color_id={$color_id}, size_id={$size_id}");
                    } else {
                        $errors[] = "Failed to add variant for size $size_id";
                        Logger::error("ProductController::addProductVariants", "Variant insert failed for size_id={$size_id}");
                    }
                }

                // --- Final Response ---
                if (empty($errors)) {
                    return ['status' => 'success', 'message' => "All $successCount variants added successfully"];
                }

                return [
                    'status' => 'partial_success',
                    'message' => 'Some variants failed to save',
                    'errors' => $errors,
                ];
            }catch(Exception $e){
                Logger::error("ProductController::addProductVariants", $e->getMessage());
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
            
        }
    }