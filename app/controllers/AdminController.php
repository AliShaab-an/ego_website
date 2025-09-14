<?php

    require_once __DIR__ .'/../models/User.php';
    require_once __DIR__ . '/../core/Session.php';
    require_once __DIR__ . '/../core/Auth.php';
    require_once __DIR__ . '/../core/Helper.php';

    class AdminController {
        public function login() {
            Session::startSession();

            $error = '';

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                if(User::emailExists($email) === false){
                    $error = "Email already exists.";
                    exit;
                }
                $user = User::verifyLogin($email,$password);

                if($user && in_array($user['role'], ['admin','super_admin'])){
                    Session::set('user_id',$user['id']);
                    Session::set('user_name',$user['name']);
                    Session::set('user_email',$user['email']);
                    Session::set('role', $user['role']);
                    Helper::redirect('index.php?action=dashboard');
                    exit;
                }else{
                    $error = "Invalid email or password.";
                }
            }
            include __DIR__ . '/../views/backend/login.php';
        }

        public function logout() {
            Session::destroySession();
            Helper::redirect('login.php?action=logout');
            exit;
        }

        public function dashboard(){
            include __DIR__ . '/../views/backend/dashboard.php';
        }

        public function categoryPage(){
            include __DIR__ . '/../views/backend/category.php';
        }

        public function ordersPage() {
            include __DIR__ . '/../views/backend/orders.php';
        }

        public function customersPage(){
            include __DIR__ . '/../views/backend/customers.php';
        }

        public function adminsPage(){
            include __DIR__ . '/../views/backend/admins.php';
        }

        public function productsPage(){
            include __DIR__ . '/../views/backend/addProducts.php';
        }
    }