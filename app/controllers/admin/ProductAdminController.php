<?php
    require_once __DIR__ . '/../../config/path.php';
    require_once MODELS . 'Product.php';
    require_once MODELS . "ProductVariant.php";
    require_once MODELS . "ProductImages.php";
    require_once MODELS . "ProductDiscount.php";


    class ProductAdminController{

        public function createProduct(){
            
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $base_price = floatval($_POST['base_price'] ?? 0);
            $weight = floatval($_POST['weight'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0);
            $is_top = isset($_POST['is_top']) ? 1 : 0;

            
             // Validate basic product fields
            if($name === '' || $base_price <= 0 || $category_id <= 0){
                return ['status' => 'error', 'message' => 'Invalid product data'];
            }

            // ===== 2. VALIDATE VARIANTS =====
            $variants = $_POST['variants'] ?? [];
            if(empty($variants) || !is_array($variants)){
                return ['status' => 'error', 'message' => "Please add at least one variant before saving."
                ];
            }

            foreach($variants as $index => $variant){
                $colorId = $variant['color_id'] ?? null;
                $sizeId  = $variant['size_id'] ?? null;
                $quantity = $variant['quantity'] ?? null;

                if(empty($colorId) || !is_numeric($colorId)){
                    return ['status' => 'error', 'message' => 'Each variant must have a color selected.'];
                }

                if (empty($sizeId) || !is_numeric($sizeId)) {
                    return ['status' => 'error', 'message' => 'Each variant must have a size selected.'];
                }

                if($quantity === '' || !is_numeric($quantity) || intval($quantity) < 0){
                    return ['status' => 'error', 'message' => 'Each variant must have a valid quantity (0 or more).'];
                }
            }

             // ===== 3. VALIDATE IMAGES =====
            $hasImages = false;
            if (isset($_FILES['variants']['name']) && is_array($_FILES['variants']['name'])) {
                foreach ($_FILES['variants']['name'] as $variantIndex => $variantFiles) {
                    if (!empty($variantFiles['images']) && count(array_filter($variantFiles['images'])) > 0) {
                        $hasImages = true;
                        break;
                    }
                }
            }
            if (!$hasImages) {
                return ['status' => 'error', 'message' => 'Please upload at least one product image.'];
            }
            try{
                $productId = Product::create([
                'name'        => $name,
                'description' => $description,
                'base_price'  => $base_price,
                'weight'      => $weight,
                'category_id' => $category_id,
                'is_top' => $is_top,
                'is_active' => 1
                ]);

                $check = DB::query("SELECT * FROM products WHERE id = ?", [$productId])->fetch(PDO::FETCH_ASSOC);
                file_put_contents(__DIR__ . '/../../logs/controller.log',
                    "After insert, fetched product #$productId:\n" . print_r($check, true) . "\n",
                    FILE_APPEND
                );

                $isFirstImage = true;

                foreach ($variants as $index => $variant) {
                    $variantId = ProductVariant::create($productId, [
                        'color_id' => $variant['color_id'],
                        'size_id'  => $variant['size_id'],
                        'price'    => $variant['price'] !== '' ? floatval($variant['price']) : 0,
                        'quantity' => intval($variant['quantity'] ?? 0),
                        'is_active' => 1
                    ]);

                    if(isset($_FILES['variants']['name'][$index]['images'])){
                        $fileNames = $_FILES['variants']['name'][$index]['images'];
                        $tmpNames  = $_FILES['variants']['tmp_name'][$index]['images'];
                        $errors    = $_FILES['variants']['error'][$index]['images'];

                        for($i = 0; $i < count($fileNames); $i++){
                            $fileName = $fileNames[$i];
                            $tmpName  = $tmpNames[$i];
                            $error    = $errors[$i];


                            if($error === UPLOAD_ERR_OK && is_uploaded_file($tmpName)){
                                $uniqueName = uniqid("p{$productId}_") . "_" . basename($fileName);
                                $uploadDir  = __DIR__ . '/../../../public/admin/uploads/products/';

                                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                                $destination = $uploadDir . $uniqueName;

                                if(move_uploaded_file($tmpName, $destination)){
                                    $relativePath = "admin/uploads/products/" . $uniqueName;

                                    $isMain = $isFirstImage ? 1 : 0;
                                    $isFirstImage = false;


                                    ProductImages::addImage([
                                        'product_id'    => $productId,
                                        'variant_id'    => $variantId,
                                        'color_id'      => $variant['color_id'] ?? null,
                                        'image_path'    => $relativePath,
                                        'is_main'       => $isMain,
                                        'display_order' => $i + 1
                                    ]);
                                }else{
                                    Logger::error("ProductController::addProduct", "Failed to save image: $fileName");
                                    return ['status' => 'error', 'message' => "Failed to save image: $fileName"];
                                }
                            }else{
                                Logger::error("ProductController::addProduct", "Image upload error for $fileName");
                                return ['status' => 'error', 'message' => "Image upload error for $fileName"];
                            }
                        }
                    }
                }
                
                // ===== 4. HANDLE DISCOUNT =====
                $discountPercentage = floatval($_POST['discount'] ?? 0);
                $isDiscountActive = isset($_POST['is_active']) ? 1 : 0;
                
                // Validate discount logic
                if ($isDiscountActive && $discountPercentage <= 0) {
                    return ['status' => 'error', 'message' => 'Please enter a valid discount percentage when marking discount as active.'];
                }
                
                // Only create discount if percentage is provided and > 0
                if ($discountPercentage > 0) {
                    try {
                        ProductDiscount::create($productId, $discountPercentage, $isDiscountActive);
                        Logger::info("ProductController::addProduct", "Discount created: {$discountPercentage}% (Active: {$isDiscountActive})");
                    } catch (Exception $e) {
                        Logger::error("ProductController::addProduct", "Failed to create discount: " . $e->getMessage());
                        return ['status' => 'error', 'message' => 'Failed to create product discount: ' . $e->getMessage()];
                    }
                }
                
                return [
                    'status' => 'success',
                    'product_id' => $productId,
                    'message' => 'Product with variants and images added successfully'
                ];

            }catch(Exception $e){
                Logger::error("ProductController::addProduct", $e->getMessage());
                return ['status'=> 'error', 'message' => $e->getMessage()];
            }
        }

        public function listProducts(){

            $limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            $filters = [
                'search'   => $_GET['search'] ?? '',
                'category' => $_GET['category'] ?? '',
                'status'   => $_GET['status'] ?? '',
                'top'      => $_GET['top'] ?? ''
            ];

            try {
                $products = Product::getAllPaginated($limit, $offset, $filters);
                $total = Product::countAll($filters);

                return [
                    'status' => 'success',
                    'data' => $products,
                    'total' => $total,
                    'has_more' => ($offset + $limit) < $total
                ];

            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function deleteProduct(){
            $id = $_POST['id'] ?? 0;
            if (!$id) {
                return ['status' => 'error', 'message' => 'Invalid product ID'];
            }

            try {
                Product::delete($id);
                return ['status' => 'success', 'message' => 'Product deleted successfully.'];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }


        public function getProductById() {
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                return ['status' => 'error', 'message' => 'Invalid product ID'];
            }

            try {
                $product = Product::findById($id);
                if (!$product) {
                    return ['status' => 'error', 'message' => 'Product not found'];
                }

                // Get variants + images + discount
                $variants = ProductVariant::getById($id);
                $images = ProductImages::getById($id);
                $discount = ProductDiscount::getByProductId($id);
                
                return [
                    'status' => 'success',
                    'data' => [
                        'product' => $product,
                        'variants' => $variants,
                        'images' => $images,
                        'discount' => $discount
                    ]
                ];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Failed to load product: ' . $e->getMessage()];
            }
        }

        public function toggleProductStatus() {
            try {
                $id = (int)($_POST['id'] ?? 0);
                $action = $_POST['action'] ?? 0;

                file_put_contents(__DIR__ . '/../../logs/app.log', print_r($_POST, true), FILE_APPEND);

                if ($id <= 0) {
                    return ['status' => 'error', 'message' => 'Invalid product data'];
                }

                $status = ($action === 'activate') ? 1 : 0;


                Product::updateStatus($id, $status);
                ProductVariant::updateStatusByProduct($id, $status);

                $msg = $status
                    ? 'Product activated successfully'
                    : 'Product deactivated successfully';
                return['status' => 'success', 'message' => $msg];

            } catch (Exception $e) {
                return['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function updateProduct(){
            try {
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) throw new Exception("Invalid product ID.");

                // Debug logging for form data
                Logger::info("update-product", "Form data received: " . json_encode($_POST));
                Logger::info("update-product", "Category ID: " . ($_POST['category_id'] ?? 'NOT_SET'));

                // ===== 1. VALIDATE BASIC PRODUCT FIELDS =====
                $name = trim($_POST['name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $base_price = floatval($_POST['base_price'] ?? 0);
                $weight = floatval($_POST['weight'] ?? 0);
                $category_id = intval($_POST['category_id'] ?? 0);

                if($name === ''){
                    return ['status' => 'error', 'message' => 'Product name is required.'];
                }
                if($base_price <= 0){
                    return ['status' => 'error', 'message' => 'Product price must be greater than 0.'];
                }

                // ===== 2. VALIDATE VARIANTS =====
                $variants = $_POST['variants'] ?? [];
                if(!empty($variants) && is_array($variants)){
                    foreach($variants as $index => $variant){
                        $colorId = $variant['color_id'] ?? null;
                        $sizeId  = $variant['size_id'] ?? null;
                        $quantity = $variant['quantity'] ?? null;

                        if(empty($colorId) || !is_numeric($colorId)){
                            return ['status' => 'error', 'message' => 'Each variant must have a color selected.'];
                        }

                        if (empty($sizeId) || !is_numeric($sizeId)) {
                            return ['status' => 'error', 'message' => 'Each variant must have a size selected.'];
                        }

                        if($quantity === '' || !is_numeric($quantity) || intval($quantity) < 0){
                            return ['status' => 'error', 'message' => 'Each variant must have a valid quantity (0 or more).'];
                        }
                    }
                }

                // ===== 3. VALIDATE DISCOUNT =====
                $discountPercentage = floatval($_POST['discount'] ?? 0);
                $isDiscountActive = isset($_POST['is_active']) ? 1 : 0;
                
                if ($isDiscountActive && $discountPercentage <= 0) {
                    return ['status' => 'error', 'message' => 'Please enter a valid discount percentage when marking discount as active.'];
                }

                Product::update($id, $_POST);

                // Update or create variants
                if (!empty($_POST['variants']) && is_array($_POST['variants'])) {
                    foreach ($_POST['variants'] as $variantData) {
                        if (!empty($variantData['id'])) {
                            ProductVariant::update($variantData['id'], $variantData);
                        } else {
                            ProductVariant::create($id, $variantData);
                        }
                    }
                }

                // Handle deleted images (marked in form)
                if (!empty($_POST['deleted_images'])) {
                    Logger::info("update-product", "Deleting images: " . json_encode($_POST['deleted_images']));
                    foreach ($_POST['deleted_images'] as $imgId) {
                        ProductImages::deleteById((int)$imgId);
                        Logger::info("update-product", "Deleted image ID: " . $imgId);
                    }
                }

                // TODO: Handle new image uploads in future update
                // if (!empty($_FILES['variants']['name'])) {
                //     $this->handleImageUploads($id, $_FILES);
                // }

                // ===== HANDLE DISCOUNT UPDATE =====
                try {
                    if ($discountPercentage > 0) {
                        // Create or update discount
                        ProductDiscount::update($id, $discountPercentage, $isDiscountActive);
                        Logger::info("update-product", "Discount updated: {$discountPercentage}% (Active: {$isDiscountActive})");
                    } else {
                        // Remove discount if percentage is 0 or empty
                        ProductDiscount::deleteByProductId($id);
                        Logger::info("update-product", "Discount removed for product ID: {$id}");
                    }
                } catch (Exception $e) {
                    Logger::error("update-product", "Failed to handle discount: " . $e->getMessage());
                    return ['status' => 'error', 'message' => 'Failed to update product discount: ' . $e->getMessage()];
                }

                return([
                    'status' => 'success',
                    'message' => 'Product updated successfully.'
                ]);
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function quickUpdate(){
            try{
                $id = (int)($_POST['id'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $base_price = floatval($_POST['base_price'] ?? 0);
                $is_top = isset($_POST['is_top']) ? 1 : 0;

                if ($id <= 0 || $name === '' || $base_price <= 0) {
                    return ['status' => 'error', 'message' => 'Invalid product data'];
                }

                $data = [
                    'name' => $name,
                    'base_price' => $base_price,
                    'is_top' => $is_top
                ];

                Product::quickUpdate($id,$data);
                return ['status' => 'success', 'message' => 'Category updated successfully'];
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

    }
