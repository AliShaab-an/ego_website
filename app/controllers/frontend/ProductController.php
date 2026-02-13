<?php 

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

        public function getProductsByCategoryName($categoryName, $limit = 8){
            try{
                require_once MODELS . 'Category.php';
                $category = Category::findByName($categoryName);
                
                if (!$category) {
                    return [];
                }
                
                return Product::getAllProducts($limit, 0, ['categories' => [$category['id']]]);
            }catch(Exception $e){
                return [];
            }
        }

        public function listProducts() {
            try{
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
                }

                // Build cache key with version for automatic invalidation
                $shopVersion = Cache::get('shop:version') ?: 1;
                $cacheKey = 'shop:v' . $shopVersion . ':products:' . md5(json_encode([
                    'page' => $page,
                    'limit' => $limit,
                    'filters' => $filters
                ]));

                // Cache products and count for 2 minutes
                $result = Cache::remember($cacheKey, 120, function() use ($limit, $offset, $filters) {
                    $products = Product::getAllProducts($limit, $offset, $filters);
                    $total = Product::countAllProducts($filters);
                    
                    return [
                        'products' => $products,
                        'total' => $total,
                        'has_more' => count($products) === (int)$limit
                    ];
                });

                return [
                    'status' => 'success',
                    'data' => $result['products'],
                    'total' => $result['total'],
                    'has_more' => $result['has_more']
                ];
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        

        public function getProductById($id){
            $productData = Product::getProductById($id);

            if(!$productData){
                header("Location: " . url('404.php'));
                exit;
            }

            $product = [
                'id' => $productData[0]['product_id'],
                'name' => $productData[0]['name'],
                'description' => $productData[0]['description'],
                'base_price' => $productData[0]['base_price'],
                'discount_percentage' => $productData[0]['discount_percentage'] ?? 0,
                'discount_active' => $productData[0]['discount_active'] ?? 0,
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