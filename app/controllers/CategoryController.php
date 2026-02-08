<?php

    class CategoryController{
        
        public function listCategories(){
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
            $offset = ($page - 1) * $limit;

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
            return ['status' => 'success', 'message' => 'Category deleted successfully'];
        }

        // Frontend Function

        public function listCategoriesWithProducts() {
            $data = Category::getCategoriesWithProducts(4);
            return ['status' => 'success', 'data' => $data];
        }

    }