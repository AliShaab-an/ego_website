<?php

    require_once __DIR__ . '/../core/DB.php';

    class User{

        public static function findUserByEmail($email){
            return DB::query("SELECT * FROM users WHERE email = ?", [$email]) -> fetch();
        }

        public static function findById($id){
            return DB::query("SELECT * FROM users WHERE id = ?", [$id]) -> fetch();
        }

        public static function  emailExists($email){
            $stmt = DB::query("SELECT id FROM  users WHERE email = ?", [$email]);
            return $stmt->fetch() !== false;
        }

        public static function getByEmail($email) {
            return DB::query("SELECT * FROM users WHERE email = ?", [$email])->fetch(PDO::FETCH_ASSOC);
        }

        public static function createUser($data){
            $hashedPassword = password_hash($data['password'],PASSWORD_BCRYPT);
            try{
                DB::query("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)", [
                    $data['name'],
                    $data['email'],
                    $hashedPassword,
                    $data['role'] ?? 'customer'
                ]);
            return DB::getConnection()->lastInsertId();
            }catch(Exception $e){
                error_log("User register error: " . $e->getMessage());
            }
            
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


        public static function getCustomersCountLast7Days(){
            $stmt = DB::query("
                SELECT COUNT(*) FROM users WHERE role = 'customer' AND created_at >= NOW() - INTERVAL 7 DAY
                ");
                return (int) $stmt->fetchColumn();
        }

        public static function getAllAdmins(){
            try{
                $stmt = DB::query("SELECT id,name,email,role FROM users WHERE role IN ('admin','super_admin') ORDER By name ASC");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }catch(Exception $e){
                throw new Exception("Failed to fetch admins: " . $e->getMessage());
            }
        }

        public static function countAll() {
            try{
                $stmt = DB::query("SELECT COUNT(*) AS count FROM users");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (int)$row['count'];
            }catch(PDOException $e){
                throw new Exception("Failed to count admins: " . $e->getMessage());
            }
        }

        public static function createAdmin($name, $email, $password, $role = 'admin') {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            try{
                DB::query(
                    "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())",
                    [$name, $email, $hashedPassword, $role]
                );
                return DB::getConnection()->lastInsertId();
            }catch(PDOException $e){
                throw new Exception("Failed to create admin: " . $e->getMessage());
            
            }
        }

        public static function getAdminById($id) {
            $stmt = DB::query("SELECT id, name, email, role, created_at FROM users WHERE id = ?", [$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function getAdminByEmail($email) {
            $stmt = DB::query("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function updateAdmin($id, $name, $email, $role, $password = null) {
            
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                DB::query(
                    "UPDATE users SET name = ?, email = ?, password = ?, role = ? WHERE id = ?",
                    [$name, $email, $hashedPassword, $role, $id]
                );
            } else {
                DB::query(
                    "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?",
                    [$name, $email, $role, $id]
                );
            }
        }

        public static function deleteAdmin($id) {
            DB::query("DELETE FROM users WHERE id = ?", [$id]);
        }

    }