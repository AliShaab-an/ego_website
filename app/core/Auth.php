<?php

    require_once __DIR__ . '/../core/Session.php';

    class Auth{

        public static function checkAdmin(){
            Session::startSession();
            if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
                header("Location: ../admin/login.php");
                exit();
            }
        }

        public static function checkCustomer() {
        Session::startSession();
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
            header("Location: ../login.php");
            exit();
        }
    }
    }