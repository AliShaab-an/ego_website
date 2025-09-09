<?php

    require_once __DIR__ . '/../core/DB.php';

    class User{

        public static function findUserByEmail($email){
            return DB::query("SELECT * FROM users WHERE email = ?", [$email]) -> fetch();
        }

        public static function findById($id){
            return DB::query("SELECT * FROM users WHERE id = ?", [$id]) -> fetch();
        }

        public static function createUser($data){
            $hashedPassword = password_hash($data['password'],PASSWORD_BCRYPT);
            DB::query("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)", [
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['role'] ?? 'customer'
            ]);
            return DB::getConnection()->lastInsertId();
        }

        public static function verifyLogin($email, $password){
            $user = self::findUserByEmail($email);
            if($user && password_verify($password, $user['password'])){
                return $user;
            }
            return false;
        }


        public static function updateUser($id, $data){
            DB::query("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?", [
                $data['name'],
                $data['email'],
                $data['role'],
                $id
            ]);
        }

        public static function delete($id){
            DB::query("DELETE FROM users WHERE id = ?", [$id]);
        }
    }