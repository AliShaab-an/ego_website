<?php 

    require_once __DIR__ . '/../core/DB.php';

    class Sizes{
        public static function getAll(){
            return DB::query("SELECT * FROM sizes ORDER BY name ASC")->fetchAll();
        }

        public static function getById($id) {
            return DB::query("SELECT * FROM sizes WHERE id = ?", [$id])->fetch();
        }

        public static function create($name, $type=null){
            DB::query("INSERT INTO sizes (name, type) VALUES (?, ?)", [$name, $type]);
            return DB::getConnection()->lastInsertId();
        }

        public static function delete($id){
            DB::query("DELETE FROM sizes WHERE id = ?", [$id]);
        }

    }