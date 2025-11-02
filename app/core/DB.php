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
            
            // Bind parameters with proper types
            if (!empty($params)) {
                foreach ($params as $index => $value) {
                    $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
                    $stmt->bindValue($index + 1, $value, $paramType);
                }
                $stmt->execute();
            } else {
                $stmt->execute($params);
            }
            
            return $stmt;

        }
    }
