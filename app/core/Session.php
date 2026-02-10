<?php
/**
 * Session Manager with Namespace Support
 * Handles separate namespaces for admin, customer, and cart data
 */
class Session {
    private static $timeout = 900;
    private static $redirect = 'login.php';
    private static $browserSessionOnly = false;

    public static function configure($seconds, $redirect, $browserSessionOnly = false) {
        self::$timeout = $seconds;
        self::$redirect = $redirect;
        self::$browserSessionOnly = $browserSessionOnly;
    }

    public static function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.gc_maxlifetime', self::$timeout);
            ini_set('session.cookie_lifetime', self::$timeout);
            
            if (self::$browserSessionOnly) {
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path' => '/',
                    'domain' => '',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            } else {
                session_set_cookie_params([
                    'lifetime' => self::$timeout,
                    'path' => '/',
                    'domain' => '',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            
            session_start();

            // Check for session timeout
            if (!self::$browserSessionOnly && isset($_SESSION['LAST_ACTIVITY'])) {
                if (time() - $_SESSION['LAST_ACTIVITY'] > self::$timeout) {
                    self::destroySession();
                    header("Location: " . self::$redirect . "?timeout=1");
                    exit;
                }
            }
            
            if (!self::$browserSessionOnly) {
                $_SESSION['LAST_ACTIVITY'] = time();
            }
        }
    }

    /**
     * Set data in a specific namespace
     * @param string $namespace (e.g., 'admin', 'customer', 'cart')
     * @param string $key
     * @param mixed $value
     */
    public static function setInNamespace(string $namespace, string $key, $value): void {
        if (!isset($_SESSION[$namespace])) {
            $_SESSION[$namespace] = [];
        }
        $_SESSION[$namespace][$key] = $value;
    }

    /**
     * Get data from a specific namespace
     * @param string $namespace
     * @param string|null $key If null, returns entire namespace
     * @return mixed
     */
    public static function getFromNamespace(string $namespace, ?string $key = null) {
        if ($key === null) {
            return $_SESSION[$namespace] ?? null;
        }
        return $_SESSION[$namespace][$key] ?? null;
    }

    /**
     * Clear a specific namespace only (e.g., admin or customer)
     * Cart and other data remain intact
     */
    public static function clearNamespace(string $namespace): void {
        if (isset($_SESSION[$namespace])) {
            unset($_SESSION[$namespace]);
        }
    }

    /**
     * Regenerate session ID (security best practice on login/logout)
     */
    public static function regenerateId(): void {
        session_regenerate_id(true);
    }

    // Legacy methods for backward compatibility
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function getKey($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function getCurrentUser() {
        // Check customer first, then admin
        if (isset($_SESSION['customer']['id'])) {
            return $_SESSION['customer']['id'];
        }
        if (isset($_SESSION['admin']['id'])) {
            return $_SESSION['admin']['id'];
        }
        return null;
    }

    /**
     * Destroy entire session (use rarely - e.g., security breach)
     */
    public static function destroySession() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}