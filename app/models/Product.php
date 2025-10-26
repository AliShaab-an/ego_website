<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Product {
        //frontend functions
        public static function getAllProducts($limit, $offset,$filters=[]) {
            try {
                // Debug logging
                error_log("Product::getAllProducts - Filters: " . print_r($filters, true));
                
                $limit = (int)$limit;
                $offset = (int)$offset;

                $sql = "
                    SELECT DISTINCT p.id, p.name, p.base_price,
                    COALESCE(
                        (SELECT image_path 
                        FROM product_images 
                        WHERE product_id = p.id AND is_main = 1 
                        LIMIT 1),
                        'admin/assets/no-image.png'
                    ) AS image_path,
                    pd.discount_percentage,
                    pd.is_active AS discount_active,
                    CASE 
                        WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                            p.base_price * (1 - pd.discount_percentage / 100)
                        ELSE NULL
                    END AS discounted_price
                FROM products p
                LEFT JOIN product_variants v ON v.product_id = p.id
                LEFT JOIN product_discounts pd ON p.id = pd.product_id
                WHERE p.is_active = 1
                ";

                $params = [];

                // Category filter
                if (!empty($filters['categories'])) {
                    $in = implode(',', array_fill(0, count($filters['categories']), '?'));
                    $sql .= " AND p.category_id IN ($in)";
                    $params = array_merge($params, $filters['categories']);
                    error_log("Category filter applied: " . print_r($filters['categories'], true));
                }

                // Color filter
                if (!empty($filters['colors'])) {
                    $in = implode(',', array_fill(0, count($filters['colors']), '?'));
                    $sql .= " AND v.color_id IN ($in)";
                    $params = array_merge($params, $filters['colors']);
                }

                // Size filter
                if (!empty($filters['sizes'])) {
                    $in = implode(',', array_fill(0, count($filters['sizes']), '?'));
                    $sql .= " AND v.size_id IN ($in)";
                    $params = array_merge($params, $filters['sizes']);
                }

                // Price range
                if (!empty($filters['minPrice']) || !empty($filters['maxPrice'])) {
                    $sql .= " AND p.base_price BETWEEN ? AND ?";
                    $params[] = (float)$filters['minPrice'];
                    $params[] = (float)$filters['maxPrice'];
                }

                // Sorting + pagination
                $sql .= " ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";

                error_log("Final SQL: " . $sql);
                error_log("SQL Params: " . print_r($params, true));

                $stmt = DB::getConnection()->prepare($sql);
                $stmt->execute($params);

                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Products returned: " . count($result));

                return $result;
            } catch (Exception $e) {
                throw new Exception("Failed to fetch filtered products: " . $e->getMessage());
            }
        }

        public static function countAllProducts($filters=[]) {
            try {
                $sql = "
                    SELECT COUNT(DISTINCT p.id) as total
                    FROM products p
                    LEFT JOIN product_variants v ON v.product_id = p.id
                    WHERE p.is_active = 1
                ";

                $params = [];

                if (!empty($filters['categories'])) {
                    $in = implode(',', array_fill(0, count($filters['categories']), '?'));
                    $sql .= " AND p.category_id IN ($in)";
                    $params = array_merge($params, $filters['categories']);
                }

                if (!empty($filters['colors'])) {
                    $in = implode(',', array_fill(0, count($filters['colors']), '?'));
                    $sql .= " AND v.color_id IN ($in)";
                    $params = array_merge($params, $filters['colors']);
                }

                if (!empty($filters['sizes'])) {
                    $in = implode(',', array_fill(0, count($filters['sizes']), '?'));
                    $sql .= " AND v.size_id IN ($in)";
                    $params = array_merge($params, $filters['sizes']);
                }

                if (!empty($filters['minPrice']) || !empty($filters['maxPrice'])) {
                    $sql .= " AND p.base_price BETWEEN ? AND ?";
                    $params[] = (float)$filters['minPrice'];
                    $params[] = (float)$filters['maxPrice'];
                }

                $stmt = DB::getConnection()->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                return (int)$result['total'];
            } catch (Exception $e) {
                throw new Exception("Failed to count products: " . $e->getMessage());
            }
        }

        public static function create($data) {
            try {
                // Handle category_id - set to NULL if empty or 0, validate if provided
                $categoryId = null;
                if (!empty($data['category_id']) && intval($data['category_id']) > 0) {
                    $categoryId = intval($data['category_id']);
                    
                    // Validate that the category exists
                    $stmt = DB::query("SELECT id FROM categories WHERE id = ?", [$categoryId]);
                    if (!$stmt->fetch()) {
                        throw new Exception("Invalid category ID: Category does not exist.");
                    }
                }
                
                $sql = "INSERT INTO products (name, description, base_price, weight, category_id, is_top, is_active, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, 1, NOW())";
                DB::query($sql, [
                    ucfirst(trim($data['name'] ?? '')),
                    trim($data['description'] ?? ''),
                    floatval($data['base_price'] ?? 0),
                    !empty($data['weight']) ? floatval($data['weight']) : null,
                    $categoryId,
                    !empty($data['is_top']) ? 1 : 0,

                ]);
                return DB::getConnection()->lastInsertId();
            } catch (Exception $e) {
                throw new Exception("Failed to create product: " . $e->getMessage());
            }
        }

        public static function update($id, $data) {
            try {
                // Handle category_id - set to NULL if empty or 0, validate if provided
                $categoryId = null;
                if (!empty($data['category_id']) && intval($data['category_id']) > 0) {
                    $categoryId = intval($data['category_id']);
                    
                    // Validate that the category exists
                    $stmt = DB::query("SELECT id FROM categories WHERE id = ?", [$categoryId]);
                    if (!$stmt->fetch()) {
                        throw new Exception("Invalid category ID: Category does not exist.");
                    }
                }
                
                $sql = "UPDATE products 
                        SET name = ?, description = ?, base_price = ?, weight = ?, category_id = ?, is_top = ?
                        WHERE id = ?";
                DB::query($sql, [
                    ucfirst(trim($data['name'] ?? '')),
                    trim($data['description'] ?? ''),
                    floatval($data['base_price'] ?? 0),
                    !empty($data['weight']) ? floatval($data['weight']) : null,
                    $categoryId,
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
                // First try to get products marked as top products
                $sql = "
                SELECT 
                    p.id, 
                    p.name, 
                    p.base_price, 
                    COALESCE(pi.image_path, 'admin/assets/no-image.png') AS image_path,
                    pd.discount_percentage,
                    pd.is_active AS discount_active,
                    CASE 
                        WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                            p.base_price * (1 - pd.discount_percentage / 100)
                        ELSE NULL
                    END AS discounted_price
                FROM products p
                LEFT JOIN product_images pi 
                    ON p.id = pi.product_id 
                    AND pi.is_main = 1
                LEFT JOIN product_discounts pd ON p.id = pd.product_id
                WHERE p.is_top = 1 
                AND p.is_active = 1
                ORDER BY p.created_at DESC
                LIMIT :limit
            ";

            $stmt = DB::getConnection()->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // If no top products found, get the newest products instead
            if (empty($topProducts)) {
                $sql = "
                SELECT 
                    p.id, 
                    p.name, 
                    p.base_price, 
                    COALESCE(pi.image_path, 'admin/assets/no-image.png') AS image_path,
                    pd.discount_percentage,
                    pd.is_active AS discount_active,
                    CASE 
                        WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                            p.base_price * (1 - pd.discount_percentage / 100)
                        ELSE NULL
                    END AS discounted_price
                FROM products p
                LEFT JOIN product_images pi 
                    ON p.id = pi.product_id 
                    AND pi.is_main = 1
                LEFT JOIN product_discounts pd ON p.id = pd.product_id
                WHERE p.is_active = 1
                ORDER BY p.created_at DESC
                LIMIT :limit
                ";
                
                $stmt = DB::getConnection()->prepare($sql);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $topProducts;
            }catch(Exception $e){
                throw new Exception("Failed to get top products: " . $e->getMessage());
            }   
        }

        public static function getNewProducts($limit = 8){
            $limit = (int)$limit;

            try{
                $sql = "
                    SELECT 
                        p.id, 
                        p.name, 
                        p.base_price, 
                        COALESCE(
                            (SELECT pi.image_path 
                            FROM product_images pi 
                            WHERE pi.product_id = p.id AND pi.is_main = 1 
                            LIMIT 1),
                            'admin/assets/no-image.png'
                        ) AS image_path,
                        pd.discount_percentage,
                        pd.is_active AS discount_active,
                        CASE 
                            WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                                p.base_price * (1 - pd.discount_percentage / 100)
                            ELSE NULL
                        END AS discounted_price
                    FROM products p
                    LEFT JOIN product_discounts pd ON p.id = pd.product_id
                    WHERE p.is_active = 1
                    ORDER BY p.created_at DESC
                    LIMIT :limit
                ";

                $stmt = DB::getConnection()->prepare($sql);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            }catch(Exception $e){
                throw new Exception("Failed to get new products: " . $e->getMessage());
            }
        }


        public static function getProductById($id){
            try{
                        $sql = "
                    SELECT 
                        p.id AS product_id,
                        p.name,
                        p.description,
                        p.base_price,

                        -- Get all images as comma-separated list
                        GROUP_CONCAT(DISTINCT pi.image_path ORDER BY pi.id SEPARATOR ',') AS images,

                        -- Variants info
                        v.id AS variant_id,
                        v.quantity,
                        v.price AS variant_price,
                        c.name AS color_name,
                        c.hex_code AS color_hex,
                        s.name AS size_name

                    FROM products p
                    LEFT JOIN product_variants v ON p.id = v.product_id
                    LEFT JOIN colors c ON v.color_id = c.id
                    LEFT JOIN sizes s ON v.size_id = s.id
                    LEFT JOIN product_images pi ON p.id = pi.product_id

                    WHERE p.id = ?
                    GROUP BY v.id
                ";

                $stmt = DB::getConnection()->prepare($sql);
                $stmt->execute([$id]);
                $product = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $product ?: false;
            }catch(Exception $e){
                throw new Exception("Failed to get product: " . $e->getMessage());
            }
        }

        //admin panel product management 

        public static function getAllPaginated($limit = 10, $offset = 0, $filters = []){
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
            WHERE 1=1
            ";

                if (!empty($filters['search'])) {
                    $sql .= " AND p.name LIKE :search";
                    $params['search'] = "%" . $filters['search'] . "%";
                }

                if (!empty($filters['category'])) {
                    $sql .= " AND p.category_id = :category";
                    $params['category'] = (int)$filters['category'];
                }

                if (isset($filters['status']) && $filters['status'] !== '') {
                    $sql .= " AND p.is_active = :status";
                    $params['status'] = (int)$filters['status'];
                }

                if (isset($filters['top']) && $filters['top'] !== '') {
                    $sql .= " AND p.is_top = :top";
                    $params['top'] = (int)$filters['top'];
                }

                $sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";

                $stmt = DB::getConnection()->prepare($sql);

                foreach ($params as $key => $val) {
                    $stmt->bindValue(":$key", $val);
                }
                
                $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);

                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                throw new Exception("Failed to fetch products: " . $e->getMessage());
            }
        }

        public static function countAll($filters = []){
            try {
                $params = [];
                $sql = "SELECT COUNT(*) AS total FROM products p WHERE 1=1";

                if (!empty($filters['search'])) {
                    $sql .= " AND p.name LIKE :search";
                    $params['search'] = "%" . $filters['search'] . "%";
                }
                if (!empty($filters['category'])) {
                    $sql .= " AND p.category_id = :category";
                    $params['category'] = (int)$filters['category'];
                }
                if (isset($filters['status']) && $filters['status'] !== '') {
                    $sql .= " AND p.is_active = :status";
                    $params['status'] = (int)$filters['status'];
                }
                if (isset($filters['top']) && $filters['top'] !== '') {
                    $sql .= " AND p.is_top = :top";
                    $params['top'] = (int)$filters['top'];
                }

                $stmt = DB::getConnection()->prepare($sql);
                foreach ($params as $key => $val) {
                    $stmt->bindValue(":$key", $val);
                }
                $stmt->execute();
                $total = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
                return $total;

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

                file_put_contents(__DIR__ . '/../../logs/error.log',
                "Updating product #$id to status=$isActive\n", FILE_APPEND);

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

        public static function getProductWithVariant($productId, $size, $color) {
            try {
                // First, try to get the basic product to ensure it exists
                $basicProduct = self::findById($productId);
                if (!$basicProduct) {
                    error_log("Product ID $productId not found in database");
                    return null;
                }
                
                // Try to get product with specific variant including discount information
                $sql = "
                    SELECT 
                        p.id,
                        p.name,
                        p.description,
                        CASE 
                            WHEN pv.price IS NOT NULL AND pv.price > 0 THEN pv.price 
                            ELSE p.base_price 
                        END AS price,
                        p.base_price,
                        COALESCE(c.name, ?) AS color,
                        COALESCE(s.name, ?) AS size,
                        pi.image_path AS image,
                        pd.discount_percentage,
                        pd.is_active AS discount_active,
                        CASE 
                            WHEN pd.is_active = 1 AND pd.discount_percentage > 0 THEN
                                CASE 
                                    WHEN pv.price IS NOT NULL AND pv.price > 0 THEN 
                                        pv.price * (1 - pd.discount_percentage / 100)
                                    ELSE 
                                        p.base_price * (1 - pd.discount_percentage / 100)
                                END
                            ELSE NULL
                        END AS discounted_price
                    FROM products p
                    LEFT JOIN product_variants pv ON p.id = pv.product_id
                    LEFT JOIN colors c ON pv.color_id = c.id AND (c.name = ? OR ? IS NULL)
                    LEFT JOIN sizes s ON pv.size_id = s.id AND (s.name = ? OR ? IS NULL)
                    LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                    LEFT JOIN product_discounts pd ON p.id = pd.product_id
                    WHERE p.id = ?
                    LIMIT 1
                ";

                $stmt = DB::query($sql, [$color, $size, $color, $color, $size, $size, $productId]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($result) {
                    error_log("Found product with variant for ID $productId");
                    return $result;
                }
                
                // Fallback: return basic product info with discount check
                error_log("Using fallback for product ID $productId");
                
                // Get discount info for fallback
                $discountSql = "SELECT discount_percentage, is_active FROM product_discounts WHERE product_id = ?";
                $discountStmt = DB::query($discountSql, [$productId]);
                $discount = $discountStmt->fetch(PDO::FETCH_ASSOC);
                
                $discountedPrice = null;
                if ($discount && $discount['is_active'] && $discount['discount_percentage'] > 0) {
                    $discountedPrice = $basicProduct['base_price'] * (1 - $discount['discount_percentage'] / 100);
                }
                
                return [
                    'id' => $basicProduct['id'],
                    'name' => $basicProduct['name'],
                    'description' => $basicProduct['description'],
                    'price' => $basicProduct['base_price'],
                    'base_price' => $basicProduct['base_price'],
                    'color' => $color,
                    'size' => $size,
                    'image' => null,
                    'discount_percentage' => $discount ? $discount['discount_percentage'] : null,
                    'discount_active' => $discount ? $discount['is_active'] : 0,
                    'discounted_price' => $discountedPrice
                ];

            } catch (Exception $e) {
                file_put_contents(__DIR__ . '/../../logs/model.log', "getProductWithVariant error: " . $e->getMessage() . "\n", FILE_APPEND);
                error_log("getProductWithVariant error for product $productId: " . $e->getMessage());
                return null;
            }
        }

    }