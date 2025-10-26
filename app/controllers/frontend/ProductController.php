<?php 
    require_once __DIR__ . '/../../config/path.php';
    require_once MODELS . 'Product.php';
    require_once MODELS .'ProductVariant.php';
    require_once MODELS . 'ProductImages.php';

    class ProductController{

        public function getTopProducts(){
            try{
                return Product::getTopProducts(8);
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function getNewProducts(){
            try{
                return Product::getNewProducts(8);
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function listProducts() {
            try{
                // Debug logging
                error_log("ProductController::listProducts - GET parameters: " . print_r($_GET, true));
                
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;
                $offset = ($page - 1) * $limit;

                $filters = [
                    'categories' => isset($_GET['categories']) ? (array)$_GET['categories'] : [],
                    'colors' => isset($_GET['colors']) ? (array)$_GET['colors'] : [],
                    'sizes' => isset($_GET['sizes']) ? (array)$_GET['sizes'] : [],
                    'minPrice' => isset($_GET['minPrice']) ? (float)$_GET['minPrice'] : 0,
                    'maxPrice' => isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : 10000,
                ];

                // Handle single category parameter (for category pages)
                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    $filters['categories'] = [(int)$_GET['category']];
                    error_log("Single category filter applied: " . $_GET['category']);
                }
                
                error_log("Final filters: " . print_r($filters, true));

                $products = Product::getAllProducts($limit, $offset,$filters);
                $total = Product::countAllProducts($filters);

                return [
                    'status' => 'success',
                    'data' => $products,
                    'total' => $total,
                    'has_more' => count($products) === (int)$limit
                ];
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
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
                if (!empty($productData[0]['images'])) {
                $product['images'] = explode(',', $productData[0]['images']);
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