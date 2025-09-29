<?php 

    require_once __DIR__ . '/../models/Product.php';
    require_once __DIR__ . '/ProductImagesController.php';
    require_once __DIR__ . '/ProductVariantsController.php';
    

    class ProductController{

        public function addProduct(){
            
            file_put_contents(__DIR__ . '/../../logs/debug.log', "POST:\n" . print_r($_POST, true), FILE_APPEND);
            file_put_contents(__DIR__ . '/../../logs/debug.log', "FILES:\n" . print_r($_FILES, true), FILE_APPEND);

            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $base_price = floatval($_POST['base_price'] ?? 0);
            $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
            $category_id = intval($_POST['category_id'] ?? 0);
            $is_top = isset($_POST['is_top']) ? 1 : 0;

            file_put_contents(__DIR__ . '/../../logs/debug.log', print_r($_POST, true), FILE_APPEND);
            // Validate and process the data
            if($name === '' || $base_price <= 0 || $category_id <= 0){
                return ['status' => 'error', 'message' => 'Invalid product data'];
            }
            try{
                $productId = Product::createProduct([
                'name'        => $name,
                'description' => $description,
                'base_price'  => $base_price,
                'weight'      => $weight,
                'category_id' => $category_id,
                'is_top' => $is_top
                ]);

                $errors = [];

                if (!empty($_POST['variants']) && is_array($_POST['variants'])){
                $variantsController = new ProductVariantsController();
                foreach($_POST['variants'] as  $variant){
                    $res = $variantsController->addVariant($productId,$variant);
                        if($res['status'] !== 'success'){
                        $errors[] = $res['message'];
                        }
                    }
                }

                if(!empty($_FILES['images']['name'][0])){
                    $imageController = new ProductImagesController();
                    $imageController->uploadImages($productId, $_FILES['images']);
                }

                return [
                'status'  => empty($errors) ? 'success' : 'partial_success',
                'id'      => $productId,
                'message' => empty($errors) ? 'Product added successfully' : 'Product added but some variants failed',
                'errors'  => $errors
                ];

            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }


        public function getTopProducts(){
            return Product::getTopProducts(8);
        }

        public function getNewProducts(){
            return Product::getNewProducts(8);
        }

        public function listAllProducts($page = 1, $perPage = 12) {
            return Product::getAllProducts($page, $perPage);
        }

        public function getProductsCount(){
            return Product::getProductsCount();
        }
    }