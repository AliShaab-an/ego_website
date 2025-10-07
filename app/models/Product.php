<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Product {

        public static function getAllProducts($page = 1, $perPage = 12) {
            try {
                $page = max(1, (int)$page);
                $perPage = (int)$perPage;
                $offset = ($page - 1) * $perPage;

                // Fetch products with main image
                $sql = "SELECT p.id, p.name, p.base_price, pi.image_path
                        FROM products p
                        LEFT JOIN product_images pi 
                        ON p.id = pi.product_id AND pi.is_main = 1
                        ORDER BY p.created_at DESC
                        LIMIT $perPage OFFSET $offset";

                $products = DB::query($sql)->fetchAll(PDO::FETCH_ASSOC);

                // Count total products
                $total = DB::query("SELECT COUNT(*) FROM products")->fetchColumn();

                return [
                    'products'    => $products,
                    'totalPages'  => ceil($total / $perPage),
                    'currentPage' => $page,
                    'totalProducts' => $total
                ];
            } catch (Exception $e) {
                error_log("Error fetching products: " . $e->getMessage());
                return ['products' => [], 'totalPages' => 0, 'currentPage' => $page];
            }
        }

        public static function getProductsCount(){
            try{
                DB::query("SELECT COUNT(*) FROM products")->fetchColumn();
            }catch(Exception $e){
                error_log("Error counting products: " . $e->getMessage());
                return 0;
            }
        }


        public static function findProduct($id){
            return DB::query("SELECT * FROM products WHERE id = ?", [$id]) -> fetch();
        }


        public static function createProduct($data){
            try{
                DB::query("INSERT INTO products (name, description, base_price,weight, category_id,is_top) VALUES (?, ?, ?, ?, ?,?)", [
                    $data['name'],
                    $data['description'],
                    $data['base_price'],
                    $data['weight'],
                    $data['category_id'],
                    $data['is_top'] ?? 0
                ]);
                return DB::getConnection()->lastInsertId();
            }catch(Exception $e){
                
            }
        }

        public static function updateProduct($id,$data){
            DB::query("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?", [
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category_id'],
                $id
            ]);
            return true;
        }

        public static function deleteProduct($id){
            DB::query("DELETE FROM products WHERE id = ?", [$id]);
        }

        public static function getTopProducts($limit = 8){
            $limit = (int)$limit;
            try{
                $topProducts = DB::query("SELECT p.id, p.name, p.base_price, pi.image_path
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.is_top = 1
                ORDER BY p.created_at ASC
                LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
                
                return $topProducts;
            }catch(Exception $e){
                echo $e;
            }   
        }

        public static function getNewProducts($limit = 8){
            $limit = (int)$limit;

            try{
                $newProducts = DB::query("SELECT p.id, p.name, p.base_price, pi.image_path
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                ORDER BY p.created_at DESC
                LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);
                return $newProducts;
            }catch(Exception $e){
                error_log("getNewProducts Error:" . $e);
                return false;
            }
        }


        public static function getProductById($id){
            try{
                $sql = "SELECT 
                    p.id AS product_id,
                    p.name,
                    p.description,
                    p.base_price,
                    pi.image_path,
                    v.id AS variant_id,
                    v.quantity,
                    v.price AS variant_price,
                    c.name AS color_name,
                    c.hex_code AS color_hex,
                    s.name AS size_name
                    FROM products p
                    LEFT JOIN product_images pi ON p.id = pi.product_id
                    LEFT JOIN product_variants v ON p.id = v.product_id
                    LEFT JOIN colors c ON v.color_id = c.id
                    LEFT JOIN sizes s ON v.size_id = s.id
                    WHERE p.id = ? 
                    AND (v.quantity IS NULL OR v.quantity > 0)";

                $product = DB::query($sql, [$id])->fetchAll(PDO::FETCH_ASSOC);
                return $product;
            }catch(Exception $e){
                error_log("Error fetching product: " . $e->getMessage());
                return false;
            }
        }
        
    }