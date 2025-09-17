<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Colors{

        public static function getAllColors(){
            return DB::query("SELECT * FROM colors ORDER BY name ASC")-> fetchAll();
        }

        public static function getById($id){
            return DB::query("SELECT * FROM colors WHERE id = ?", [$id])->fetch();
        }

        public static function createColor($name,$hex=null){
            DB::query("INSERT INTO colors (name, hex_code) VALUES (?, ?)", [$name, $hex]);
            return DB::getConnection()->lastInsertId();
        }

        public static function deleteColor($id){
            DB::query("DELETE FROM colors WHERE id = ?", [$id]);
        }
    }