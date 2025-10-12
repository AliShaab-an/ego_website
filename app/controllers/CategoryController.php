<?php

    require_once __DIR__ . '/../models/Category.php';

    class CategoryController{
        
        public function listCategories(){
            try{
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

            }catch (Exception $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function addCategory(){

            $name = isset($_POST['name']) ? ucfirst(strtolower(trim($_POST['name']))) : '';
            if($name === ''){
                return ['status' => 'error', 'message' => 'Category name is required.'];
            }
            try{
                $filename = null;
                if(!empty($_FILES['image']['name'])){
                    $uploadDir = __DIR__ . '/../../public/admin/uploads/'; 
                    if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $filename = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
                }
                $id = Category::createCategory($name,$filename);
                return ['status' => 'success', 'id' =>$id,'message' => 'Category added successfully'];

            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }


        public function updateCategory() {
            $id = $_POST['id'] ?? null;
            $name = isset($_POST['name']) ? ucfirst(strtolower(trim($_POST['name']))) : '';

            if (!$id || $name === '') {
                return ['status' => 'error', 'message' => 'Missing ID or name'];
            }
            try{
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../public/admin/uploads/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $filename = uniqid() . '-' . basename($_FILES['image']['name']);
                    $targetPath = $uploadDir . $filename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $imagePath =  $filename;
                    } else {
                        return ['status' => 'error', 'message' => 'Failed to upload image'];
                    }
                }

                Category::updateCategory($id, $name, $imagePath);
                return ['status' => 'success', 'message' => 'Category updated successfully'];

            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }
        
        public function deleteCategory() {
            $id = intval($_POST['id'] ?? 0);
            
            if($id <=0){
                return ['status' => 'error', 'message' => 'Invalid category ID'];
            }
            try{
                $cat = Category::getCategoryById($id);
                if($cat && $cat['image']){
                    $file = __DIR__ .  '/../../public/admin/uploads/' . $cat['image'];
                    if(file_exists($file)) unlink($file);
                }

                Category::deleteCategory($id);
                return ['status' => 'success', 'message' => 'Category deleted successfully'];
                
            }catch(Exception $e){
                return ['status' => 'error', 'message' => $e->getMessage()];        
            }
        }

        // Frontend Function

        public function listCategoriesWithProducts() {
            $data = Category::getCategoriesWithProducts(4);
            return ['status' => 'success', 'data' => $data];
        }

    }