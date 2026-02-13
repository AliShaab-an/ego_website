<?php

    class CategoryController{
        
        public function listCategories(){
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $offset = ($page - 1) * $limit;

            // For admin pagination, don't cache
            // For frontend (all categories), cache it
            if (isset($_GET['all']) && $_GET['all'] === 'true') {
                $data = Cache::remember('shop:categories:all', 3600, fn() => Category::getAll());
                return [
                    'status' => 'success',
                    'data' => $data,
                    'total' => count($data)
                ];
            }

            $data = Category::getPaginated($limit, $offset);
            $total = Category::countAll();
            $hasMore = ($offset + $limit) < $total;

            return [
                'status' => 'success',
                'data' => $data,
                'total' => $total,
                'has_more' => $hasMore
            ];
        }

        public function addCategory(){

            $name = isset($_POST['name']) ? ucfirst(strtolower(trim($_POST['name']))) : '';
            if($name === ''){
                return ['status' => 'error', 'message' => 'Category name is required.'];
            }

            $filename = null;
            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
                try {
                    $uploadDir = __DIR__ . '/../../public/admin/uploads/categories/';
                    
                    $saved = ImageService::processUpload($_FILES['image'], $uploadDir, [600, 1200], 82);
                    $imageName = $saved[1200] ?? end($saved);
                    $filename = "admin/uploads/categories/" . $imageName;
                } catch (Exception $e) {
                    error_log("Category image upload error: " . $e->getMessage());
                    return ['status' => 'error', 'message' => 'Failed to process image: ' . $e->getMessage()];
                }
            }

            $existing = Category::findByName($name);
            
            if($existing){
                return ['status' => 'error', 'message' => 'Category already exists.'];
            }

            $id = Category::createCategory($name,$filename);

            // Invalidate cache by bumping versions
            $this->invalidateCategoryCache();

            return [
                'status'  => 'success',
                'id'      => $id,
                'message' => 'Category added successfully.'
            ];
        }


        public function updateCategory() {
            $id = $_POST['id'] ?? null;
            $name = isset($_POST['name']) ? ucfirst(strtolower(trim($_POST['name']))) : '';

            if (!$id || $name === '') {
                return ['status' => 'error', 'message' => 'Missing ID or name'];
            }

            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $uploadDir = __DIR__ . '/../../public/admin/uploads/categories/';
                    
                    $saved = ImageService::processUpload($_FILES['image'], $uploadDir, [600, 1200], 82);
                    $imageName = $saved[1200] ?? end($saved);
                    $imagePath = "admin/uploads/categories/" . $imageName;
                } catch (Exception $e) {
                    error_log("Category image update error: " . $e->getMessage());
                    return ['status' => 'error', 'message' => 'Failed to process image: ' . $e->getMessage()];
                }
            }

            Category::updateCategory($id, $name, $imagePath);
            
            // Invalidate cache by bumping versions
            $this->invalidateCategoryCache();
            
            return ['status' => 'success', 'message' => 'Category updated successfully'];
        }
        
        public function deleteCategory() {
            $id = intval($_POST['id'] ?? 0);
            
            if($id <=0){
                return ['status' => 'error', 'message' => 'Invalid category ID'];
            }

            $cat = Category::getCategoryById($id);
            if($cat && $cat['image']){
                // Handle both old and new path formats for backward compatibility
                $imagePath = $cat['image'];
                if(strpos($imagePath, 'admin/uploads/') === 0) {
                    // New format: admin/uploads/categories/filename.jpg
                    $file = __DIR__ . '/../../public/' . $imagePath;
                } else {
                    // Old format: just filename.jpg
                    $file = __DIR__ . '/../../public/admin/uploads/' . $imagePath;
                }
                
                if(file_exists($file)) unlink($file);
            }

            Category::deleteCategory($id);
            
            // Invalidate cache by bumping versions
            $this->invalidateCategoryCache();
            
            return ['status' => 'success', 'message' => 'Category deleted successfully'];
        }

        // Frontend Function

        public function listCategoriesWithProducts() {
            return Category::getCategoriesWithProducts(4);
        }

        /**
         * Invalidate category-related caches by bumping version numbers
         */
        private function invalidateCategoryCache() {
            // Delete specific category cache
            Cache::delete('shop:categories:all');
            
            // Bump shop version (categories affect product filtering)
            $shopVersion = Cache::get('shop:version') ?: 1;
            Cache::set('shop:version', $shopVersion + 1, 365 * 24 * 3600);
            
            // Bump home version (categories displayed on home page)
            $homeVersion = Cache::get('home:version') ?: 1;
            Cache::set('home:version', $homeVersion + 1, 365 * 24 * 3600);
        }

    }