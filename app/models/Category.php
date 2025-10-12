<?php

    require_once __DIR__ . '/../core/DB.php';
    class Category{
        
        public static function getPaginated($limit, $offset) {
            try{
                $limit = (int)$limit;
                $offset = (int)$offset;
                $stmt = DB::query("SELECT * FROM categories ORDER BY id DESC LIMIT $limit OFFSET $offset");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(PDOException $e){
                throw new Exception("Failed to fetch categories: " . $e->getMessage());
            }
        }


        public static function countAll() {
            try{
                $stmt = DB::query("SELECT COUNT(*) AS count FROM categories");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)$row['count'];
            }catch(PDOException $e){
                throw new Exception("Failed to count categories: " . $e->getMessage());
            }
        }

        public static function createCategory($name,$image = null){
            try{
                DB::query("INSERT INTO categories (name,image) VALUES (?,?)", [$name,$image]);
                return DB::getConnection()->lastInsertId();
            }catch(PDOException $e){
                throw new Exception("Failed to create category: " . $e->getMessage());
            }
            
        }

        public static function deleteCategory($id){
            try{
                if (!is_numeric($id) || $id <= 0) {
                    throw new Exception("Invalid category ID.");
                }
                $deleted = DB::query("DELETE FROM categories WHERE id = ?", [$id]);

                if ($deleted === 0) {
                    throw new Exception("Color not found or already deleted.");
                }
                return true;
            }catch(PDOException $e){
                throw new Exception("Failed to delete category: " . $e->getMessage());
            }
        }

        public static function updateCategory($id, $name, $image = null) {
            try{
                $id   = intval($id);
                $name = trim($name);
                
                if ($id <= 0 || $name === '') {
                    throw new Exception("Invalid category data.");
                }

                if ($image) {
                    DB::query("UPDATE categories SET name = ?, image = ? WHERE id = ?", [$name, $image, $id]);
                } else {
                    DB::query("UPDATE categories SET name = ? WHERE id = ?", [$name, $id]);
                }
                return true;
            }catch(PDOException $e){
                throw new Exception("Failed to update category: " . $e->getMessage());
            }
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