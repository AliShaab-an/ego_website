<?php

    require_once __DIR__ . '/../models/Category.php';

    class CategoryController{
        
        public function listCategories(){
            $categories = Category::getAllCategories();
            return ['status' => 'success', 'data' => $categories];
        }

        public function addCategory(){
            $name = trim($_POST['name'] ?? '');

            if($name === ''){
                return ['status' => 'error', 'message' => 'Category name is required.'];
            }

            $filename = null;

            if(!empty($_FILES['image']['name'])){
                $uploadDir = __DIR__ . '/../../public/admin/uploads/'; 
                if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $filename = time() . '_' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            }

            $id = Category::createCategory($name,$filename);
            return ['status' => 'success', 'id' =>$id,'message' => 'Category added successfully'];
        }


        public function updateCategory() {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');

            if (!$id || $name === '') {
                return ['status' => 'error', 'message' => 'Invalid data'];
            }

            Category::updateCategory($id, $name);
            return ['status' => 'success', 'message' => 'Category updated successfully'];
        }
        
        public function deleteCategory() {
            $id = intval($_POST['id'] ?? 0);
            
            if($id <=0){
                return ['status' => 'error', 'message' => 'Invalid category ID'];
            }
            
            $cat = Category::getCategoryById($id);
            if($cat && $cat['image']){
                $file = __DIR__ .  '/../../public/admin/uploads/' . $cat['image'];
                if(file_exists($file)) unlink($file);
            }

            Category::deleteCategory($id);
            return ['status' => 'success', 'message' => 'Category deleted successfully'];
        }

    }