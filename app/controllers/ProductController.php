<?php 

    require_once __DIR__ . '/../models/Product.php';

    class ProductController{

        public function addProduct(){
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $base_price = floatval($_POST['base_price'] ?? 0);
            $weight = !empty($_POST['weight']) ?floatval($_POST['weight']) : null ;
            $category_id = intval($_POST['category_id'] ?? 0);

            // Validate and process the data

            if($name === '' || $base_price <= 0 || $category_id <= 0){
                return ['status' => 'error', 'message' => 'Invalid product data'];
            }

            $productId = Product::createProduct([
                'name'        => $name,
                'description' => $description,
                'base_price'  => $base_price,
                'weight'      => $weight,
                'category_id' => $category_id,
            ]);

            if (!empty($_POST['variants']) && is_array($_POST['variants'])){
                $variantsController = new ProductVariantsController();
                foreach($_POST['variants'] as  $variant){
                    $variantsController->addVariant($productId,$variant);
                }
            }

            if(!empty($_FILES('images'))){
                $imageController = new ProductImagesController();
                $imageController->uploadImages($productId, $_FILES['images']);
            }

            return ['status' => 'success', 'id' => $productId, 'message' => 'Product added successfully'];
        }
    }