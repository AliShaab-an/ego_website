<?php

    require_once __DIR__ . '/../core/DB.php';


    class Category{
        public static function getAllCategories(){
            return DB::query("SELECT * FROM categories ORDER BY name ASC") -> fetchAll();
        }

        public static function createCategory($name){
            DB::query("INSERT INTO categories (name) VALUES (?)", [$name]);
            return DB::getConnection()->lastInsertId();
        }

        public static function deleteCategory($id){
            DB::query("DELETE FROM categories WHERE id = ?", [$id]);
        }
    }