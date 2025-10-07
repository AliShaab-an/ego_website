<?php 
    require_once __DIR__ . '/../config/path.php';
    require_once CORE . 'Logger.php';
    require_once __DIR__ . '/../models/Product.php';

    class ProductController{

        public function addProduct(){
            
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $base_price = floatval($_POST['base_price'] ?? 0);
            $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0;
            $category_id = intval($_POST['category_id'] ?? 0);
            $is_top = isset($_POST['is_top']) ? 1 : 0;

            
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

                return [
                    "status" => 'success',
                    'id' => $productId,
                    'message' => 'Product basic info added successfully'
                ];

            }catch(Exception $e){
                Logger::error("ProductController::addProduct", $e->getMessage());
                return ['status'=> 'error', 'message' => $e->getMessage()];
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

        public function getProductById($id){
            $productData = Product::getProductById($id);

            if(!$productData){
                header("Location: /Ego_website/public/404.php");
                exit;
            }

            $product = [
                'id' => $productData[0]['product_id'],
                'name' => $productData[0]['name'],
                'description' => $productData[0]['description'],
                'base_price' => $productData[0]['base_price'],
                'images' => [],
                'variants' => []
            ];

            foreach ($productData as $row) {
                if ($row['image_path'] && !in_array($row['image_path'], $product['images'])) {
                $product['images'][] = $row['image_path'];
                }
                if ($row['variant_id']) {
                    $product['variants'][] = [
                        'id' => $row['variant_id'],
                        'size' => $row['size_name'],
                        'color' => $row['color_name'],
                        'color_hex' => $row['color_hex'] ?? null,
                        'price' => $row['variant_price'] ?? $product['base_price'],
                        'quantity' => $row['quantity']
                    ];
                }
            }
            return $product;
        }
    }