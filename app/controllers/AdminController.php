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

                $user = User::verifyLogin($email,$password);
                if($user && $user['role'] === 'admin'){
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
            Auth::checkAdmin();
            include __DIR__ . '/../views/backend/dashboard.php';
        }

        
    }