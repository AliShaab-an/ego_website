<?php
    class Session{
        private static $timeout = 900;
        private static $redirect = 'login.php';
        private static $browserSessionOnly = false;

        public static function configure($seconds, $redirect, $browserSessionOnly = false){
            self::$timeout = $seconds;
            self::$redirect = $redirect;
            self::$browserSessionOnly = $browserSessionOnly;
        }

        public static function startSession(){
            if(session_status() == PHP_SESSION_NONE){
                // Configure session cookie parameters for browser-session-only if needed
                if(self::$browserSessionOnly) {
                    // Set session cookie to expire when browser closes (lifetime = 0)
                    session_set_cookie_params([
                        'lifetime' => 0, // Session cookie (expires when browser closes)
                        'path' => '/',
                        'domain' => '',
                        'secure' => false, // Set to true if using HTTPS
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]);
                }
                
                session_start();

                // Only check timeout if not using browser-session-only mode
                if(!self::$browserSessionOnly && isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > self::$timeout)){
                    self::destroySession();
                    header("Location: " . self::$redirect . "?timeout=1");
                    exit;
                }
                
                // Update last activity only if not using browser-session-only mode
                if(!self::$browserSessionOnly) {
                    $_SESSION['LAST_ACTIVITY'] = time();
                }
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
            // Clear guest cart when session is destroyed
            if(!isset($_SESSION['user_id']) && isset($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            
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