<?php 
    require_once __DIR__ . '/../config/path.php';
    require_once CORE . 'Logger.php';
    require_once MODELS . 'Product.php';
    require_once MODELS .'ProductVariant.php';
    require_once MODELS . 'ProductImages.php';

    class ProductController{

        public function getTopProducts(){
            return Product::getTopProducts(8);
        }

        public function getNewProducts(){
            return Product::getNewProducts(8);
        }

        public function listAllProducts($page = 1, $perPage = 12) {
            return Product::getAllProducts($page, $perPage);
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