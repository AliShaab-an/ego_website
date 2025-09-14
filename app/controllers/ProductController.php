<?php 


    class ProductController{

        public function addProduct(){
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $base_price = floatval($_POST['base_price'] ?? 0);
            $weight = floatval($_POST['weight'] ?? 0);
            $category_id = intval($_POST['category_id'] ?? 0);

            // Validate and process the data

            if($name === '' || $base_price <= 0 || $category_id <= 0){
                return ['status' => 'error', 'message' => 'Invalid product data'];
            }

            $id = Product::createProduct($name, $description, $base_price, $weight, $category_id);
            return ['status' => 'success', 'id' => $id, 'message' => 'Product added successfully'];
        }
    }