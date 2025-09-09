<?php

    require_once __DIR__ . '/../config/database.php';


    class DB{
        private static $instance = null;

        public static function getConnection(){
            global $pdo;
            return $pdo;
        }

        public static function query($sql, $params = []){
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;

        }
    }
