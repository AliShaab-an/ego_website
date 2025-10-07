<?php 

    require_once __DIR__ . '/../models/User.php';
    class UserController{

        public function listCustomers(){
            try{
                $customers = User::getAllCustomers();
                return ['status' => 'success', 'data' => $customers];
            }catch(Throwable $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            } 
        }

        public function listCustomersCountLast7Days(){
            try{
                $customersCount = User::getCustomersCountLast7Days();
                return ['status' => 'success', 'data' => $customersCount];
            }catch(Throwable $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function listAdmins(){
            try{
                $admins = User::getAllAdmins();
                return ['status' => 'success', 'data' => $admins];
            }catch(Throwable $e){
                return ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        public function register(){

            $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'customer';

            if ($name === '' || $email === '' || $password === '') {
                return ['status' => 'error', 'message' => 'All fields are required'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => 'error', 'message' => 'Invalid email address'];
            }
            $existingUser = User::getByEmail($email);
            if($existingUser){
                return ['status' => 'error', 'message' => 'Email already registered'];
            }

            try{
                $userId = User::createUser([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => $role
                ]);
                return ['status' => 'success', 'id' => $userId, 'message' => 'User registered successfully'];
            }catch(Exception $e){
                error_log("User register error: " . $e->getMessage());
                return ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function login(){
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if($email === '' || $password === ''){
                return ['status' => 'error', 'message' => 'All fields are required'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['status' => 'error', 'message' => 'Invalid email address'];
            }

            try{
                $userId = User::verifyLogin($email,$password);
                return ['status' => 'success', 'id' => $userId, 'message' => 'User registered successfully'];
            }catch(Exception $e){
                error_log("User register error: " . $e->getMessage());
                return ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        // public function listTotalCustomers(){
        //     try{
        //         $customersCount = User::getAllCustomers();
        //         return ['status' => 'success', 'data' => $customersCount];
        //     }catch(Throwable $e){
        //         return ['status' => 'error', 'message' => $e->getMessage()];
        //     }
        // }
    }