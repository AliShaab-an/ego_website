<?php
    class Session{
        private static $timeout = 900;
        private static $redirect = 'login.php';

        public static function configure($seconds, $redirect){
            self::$timeout = $seconds;
            self::$redirect = $redirect;
        }
        public static function startSession(){
            if(session_status() == PHP_SESSION_NONE){
                session_start();

                if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > self::$timeout)){
                    self::destroySession();
                    header("Location: " . self::$redirect . "?timeout=1");
                    exit;
                }
                $_SESSION['LAST_ACTIVITY'] = time();
            }
        }

        public static function getCurrentUser(){
            return $_SESSION['user_id'] ?? null;
        }

        public static function set($key,$value){
            $_SESSION[$key] = $value;
        }

        public static function getKey($key){
            return $_SESSION[$key] ?? null;
        }

        public static function destroySession(){
            $_SESSION = [];

            if(ini_get("session.use_cookies")){
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }
    }