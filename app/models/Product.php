<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Product {
        //frontend functions
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

        public static function create($data) {
            try {
                $sql = "INSERT INTO products (name, description, base_price, weight, category_id, is_top, is_active, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, 1, NOW())";
                DB::query($sql, [
                    ucfirst(trim($data['name'] ?? '')),
                    trim($data['description'] ?? ''),
                    floatval($data['base_price'] ?? 0),
                    !empty($data['weight']) ? floatval($data['weight']) : null,
                    intval($data['category_id'] ?? 0),
                    !empty($data['is_top']) ? 1 : 0,

                ]);
                return DB::getConnection()->lastInsertId();
            } catch (Exception $e) {
                throw new Exception("Failed to create product: " . $e->getMessage());
            }
        }

        public static function update($id, $data) {
            try {
                $sql = "UPDATE products 
                        SET name = ?, description = ?, base_price = ?, weight = ?, category_id = ?, is_top = ?
                        WHERE id = ?";
                DB::query($sql, [
                    ucfirst(trim($data['name'] ?? '')),
                    trim($data['description'] ?? ''),
                    floatval($data['base_price'] ?? 0),
                    !empty($data['weight']) ? floatval($data['weight']) : null,
                    intval($data['category_id'] ?? 0),
                    !empty($data['is_top']) ? 1 : 0,
                    intval($id)
                ]);
                return true;
            } catch (Exception $e) {
                throw new Exception("Failed to update product: " . $e->getMessage());
            }
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

        //admin panel product management 

        public static function getAllPaginated($limit = 10, $offset = 0, $search = ''){
            try {
                $params= [];
                $sql = "
                SELECT 
                    p.*, 
                    c.name AS category_name,
                    (SELECT image_path 
                    FROM product_images 
                    WHERE product_id = p.id AND is_main = 1 
                    LIMIT 1) AS main_image,
                    (SELECT GROUP_CONCAT(
                            CONCAT('Color:', COALESCE(col.name, 'N/A'),
                                '|Size:', COALESCE(s.name, 'N/A'),
                                '|Qty:', v.quantity,
                                '|Price:', v.price)
                            SEPARATOR '; '
                        )
                    FROM product_variants v
                    LEFT JOIN colors col ON v.color_id = col.id
                    LEFT JOIN sizes s ON v.size_id = s.id
                    WHERE v.product_id = p.id
                    ) AS variants_info
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.is_active = 1
            ";

                if (!empty($search)) {
                    $sql .= " AND p.name LIKE ?";
                    $params[] = "%$search%";
                }

                $limit = (int)$limit;
                $offset = (int)$offset;

                $sql .= " ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";
                

                $stmt = DB::query($sql,$params);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                throw new Exception("Failed to fetch products: " . $e->getMessage());
            }
        }

        public static function countAll($search = ''){
            try {
                if (!empty($search)) {
                    $stmt = DB::query("SELECT COUNT(*) as total FROM products WHERE name LIKE ?", ["%$search%"]);
                } else {
                    $stmt = DB::query("SELECT COUNT(*) as total FROM products");
                }
                return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            } catch (Exception $e) {
                throw new Exception("Failed to count products: " . $e->getMessage());
            }
        }

        public static function delete($id){
            try {
                DB::query("DELETE FROM products WHERE id = ?", [$id]);
            } catch (PDOException $e) {
                throw new Exception("Failed to delete product: " . $e->getMessage());
            }
        }

        public static function findById($id) {
            try{
                $stmt = DB::query("SELECT * FROM products WHERE id = ?", [$id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Failed to fetch product: " . $e->getMessage());
            }
        }

        public static function updateStatus($id, $isActive) {
            try{
                DB::query("UPDATE products SET is_active = ? WHERE id = ?", [$isActive, $id]);

            }catch(PDOException $e){
                throw new Exception("Failed to fetch product: " . $e->getMessage());
            }
        }

        public static function getActiveProducts() {
            try{
                $stmt = DB::query("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Failed to fetch active products" . $e->getMessage());
            }
        }

        public static function quickUpdate($id, $data){
            try{
                $sql = "UPDATE products 
                        SET name = ?, base_price = ?, is_top = ?
                        WHERE id = ?";

                DB::query($sql, [
                    ucfirst(trim($data['name'] ?? '')),
                    floatval($data['base_price'] ?? 0),
                    !empty($data['is_top']) ? 1 : 0,
                    intval($id)
                ]);
                return true;
            }catch(PDOException $e){
                throw new Exception("Failed to update active product" . $e->getMessage());
            }                       

        }

    }