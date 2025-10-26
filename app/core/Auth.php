<?php

    require_once __DIR__ . '/../core/Session.php';
    include __DIR__ . '/../config/path.php';
    require_once  CORE . 'Helper.php';
    
    class Auth{

        public static function checkAdmin(){
            Session::startSession();
            $role = $_SESSION['role'] ?? null;
            if(!isset($_SESSION['user_id']) || !in_array($role,['admin','super_admin'])){
                Helper::redirect('login.php');
            }
            
        }

        public static function checkCustomer() {
            Session::startSession();
            if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
                Helper::redirect('../login.php');
            }
        }

        public static function checkRoles(array $allowedRoles) {
            Session::startSession();
            $role = $_SESSION['role'] ?? null;
            if (!isset($_SESSION['user_id']) || !in_array($role, $allowedRoles)) {
                http_response_code(403); // Forbidden
                include __DIR__ . '/../views/errors/403.php';
                exit;
            }
        }
    }