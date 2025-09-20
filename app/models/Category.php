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
        
        public static function getCategoryById($id){
            DB::query("SELECT * FROM categories WHERE id = ?", [$id]);
        }


    }