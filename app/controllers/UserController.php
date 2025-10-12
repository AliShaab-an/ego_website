<?php 

    require_once __DIR__ . '/../models/User.php';
    class UserController{

        public function listUsers(){
            try{
                $customers = User::countAll();
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

        public function listAdmins() {
            try {
                $admins = User::getAllAdmins();
                return ['status' => 'success', 'data' => $admins];
            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Failed to fetch admins: ' . $e->getMessage()];
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
                return ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
            }
        }

        public function addAdmin() {
            try {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $role = $_POST['role'] ?? 'admin';

                if ($name === '' || $email === '' || $password === '') {
                    return ['status' => 'error', 'message' => 'All fields are required.'];
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return ['status' => 'error', 'message' => 'Invalid email format.'];
                }

                // Check if email already exists
                $existing = User::getAdminByEmail($email);
                if ($existing) {
                    return ['status' => 'error', 'message' => 'An admin with this email already exists.'];
                }

                $id = User::createAdmin($name, $email, $password, $role);
                return ['status' => 'success', 'id' => $id, 'message' => 'Admin added successfully.'];

            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Error adding admin: ' . $e->getMessage()];
            }
        }

        public function updateAdmin() {
            try {
                $id = intval($_POST['id'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $role = trim($_POST['role'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if ($id <= 0 || $name === '' || $email === '') {
                    return ['status' => 'error', 'message' => 'Missing or invalid data.'];
                }

                User::updateAdmin($id, $name, $email, $role, $password ?: null);
                return ['status' => 'success', 'message' => 'Admin updated successfully.'];

            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Error updating admin: ' . $e->getMessage()];
            }
        }

        public function deleteAdmin() {
            try {
                $id = intval($_POST['id'] ?? 0);
                if ($id <= 0) {
                    return ['status' => 'error', 'message' => 'Invalid admin ID.'];
                }

                User::deleteAdmin($id);
                return ['status' => 'success', 'message' => 'Admin deleted successfully.'];

            } catch (Exception $e) {
                return ['status' => 'error', 'message' => 'Error deleting admin: ' . $e->getMessage()];
            }
        }
    }