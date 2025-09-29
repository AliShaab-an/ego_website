<?php

    require_once __DIR__ . '/../core/DB.php';
    class Category{
        public static function getAllCategories(){
            return DB::query("SELECT * FROM categories ORDER BY name DESC") -> fetchAll(PDO::FETCH_ASSOC);
        }

        public static function createCategory($name,$image = null){
            DB::query("INSERT INTO categories (name,image) VALUES (?,?)", [$name,$image]);
            return DB::getConnection()->lastInsertId();
        }

        public static function deleteCategory($id){
            DB::query("DELETE FROM categories WHERE id = ?", [$id]);
        }

        public static function updateCategory($id, $name){
            DB::query("UPDATE categories SET name = ? WHERE id = ?", [$name, $id]);
        }
        
        // Frontend functions
        public static function getCategoryById($id){
            DB::query("SELECT * FROM categories WHERE id = ?", [$id]);
        }

        public static function getCategoriesWithProducts($limit = 4) {
            $categories = DB::query("SELECT * FROM categories ORDER BY name ASC")
                    ->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as &$category) {
                $category['products'] = DB::query("
                    SELECT p.id, p.name, p.base_price, pi.image_path
                    FROM products p
                    LEFT JOIN product_images pi 
                        ON p.id = pi.product_id AND pi.is_main = 1
                    WHERE p.category_id = ?
                    ORDER BY p.id DESC
                    LIMIT $limit
                ", [$category['id']])->fetchAll(PDO::FETCH_ASSOC) ?? [];
            }

            return $categories;
        }


    }