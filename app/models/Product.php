<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Product {

        public static function getAllProducts(){
            return DB::query("SELECT * FROM products ORDER BY created_at DESC") -> fetchAll();
        }

        public static function findProduct($id){
            return DB::query("SELECT * FROM products WHERE id = ?", [$id]) -> fetch();
        }


        public static function createProduct($data){
            DB::query("INSERT INTO products (name, description, price, category_id, created_at) VALUES (?, ?, ?, ?)", [
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category_id'],
            ]);
            return DB::getConnection()->lastInsertId();
        }

        public static function updateProduct($id,$data){
            DB::query("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?", [
                $data['name'],
                $data['description'],
                $data['price'],
                $data['category_id'],
                $id
            ]);
            return true;
        }

        public static function deleteProduct($id){
            DB::query("DELETE FROM products WHERE id = ?", [$id]);
        }
    }