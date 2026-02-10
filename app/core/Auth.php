<?php
/**
 * Authentication Handler with Namespace Separation
 * Manages separate admin and customer authentication
 */
class Auth {

    /**
     * ADMIN LOGIN
     * Attempt admin login with email and password
     */
    public static function attemptAdmin(string $email, string $password): bool {
        $user = User::getByEmail($email);

        if (!$user) {
            return false;
        }

        // Only allow admin and super_admin roles
        if (!in_array($user['role'], ['admin', 'super_admin'], true)) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::loginAdmin($user);
        return true;
    }

    /**
     * CUSTOMER LOGIN
     * Attempt customer login with email and password
     */
    public static function attemptCustomer(string $email, string $password): bool {
        $user = User::getByEmail($email);

        if (!$user) {
            return false;
        }

        // Only allow customer role
        if ($user['role'] !== 'customer') {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        self::loginCustomer($user);
        return true;
    }

    /**
     * Log admin into session (namespace: admin)
     */
    public static function loginAdmin(array $user): void {
        // Clear any existing customer session (prevent conflicts)
        Session::clearNamespace('customer');
        
        // Set admin session
        Session::setInNamespace('admin', 'id', $user['id']);
        Session::setInNamespace('admin', 'name', $user['name']);
        Session::setInNamespace('admin', 'email', $user['email']);
        Session::setInNamespace('admin', 'role', $user['role']);
        
        Session::regenerateId();
    }

    /**
     * Log customer into session (namespace: customer)
     */
    public static function loginCustomer(array $user): void {
        // Clear any existing admin session (prevent conflicts)
        Session::clearNamespace('admin');
        
        // Set customer session
        Session::setInNamespace('customer', 'id', $user['id']);
        Session::setInNamespace('customer', 'name', $user['name']);
        Session::setInNamespace('customer', 'email', $user['email']);
        Session::setInNamespace('customer', 'role', $user['role']);
        
        Session::regenerateId();
    }

    /**
     * ADMIN LOGOUT
     * Clears admin session only, preserves cart
     */
    public static function logoutAdmin(): void {
        Session::clearNamespace('admin');
        Session::regenerateId();
    }

    /**
     * CUSTOMER LOGOUT
     * Clears customer session only, preserves cart
     */
    public static function logoutCustomer(): void {
        Session::clearNamespace('customer');
        Session::regenerateId();
    }

    /**
     * Is admin logged in?
     */
    public static function isAdmin(): bool {
        $admin = Session::getFromNamespace('admin');
        return $admin !== null && isset($admin['id']);
    }

    /**
     * Is customer logged in?
     */
    public static function isCustomer(): bool {
        $customer = Session::getFromNamespace('customer');
        return $customer !== null && isset($customer['id']);
    }

    /**
     * Is anyone logged in (admin or customer)?
     */
    public static function check(): bool {
        return self::isAdmin() || self::isCustomer();
    }

    /**
     * Get current user data (admin or customer)
     */
    public static function user(): ?array {
        if (self::isAdmin()) {
            return Session::getFromNamespace('admin');
        }
        if (self::isCustomer()) {
            return Session::getFromNamespace('customer');
        }
        return null;
    }

    /**
     * Get current user ID
     */
    public static function id(): ?int {
        $user = self::user();
        return $user['id'] ?? null;
    }

    /**
     * Get current user role
     */
    public static function role(): ?string {
        $user = self::user();
        return $user['role'] ?? null;
    }

    /**
     * LEGACY METHOD - Attempt login (auto-detect role)
     * Use attemptAdmin() or attemptCustomer() for new code
     */
    public static function attempt(string $email, string $password): bool {
        $user = User::getByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        // Route to appropriate login based on role
        if (in_array($user['role'], ['admin', 'super_admin'], true)) {
            self::loginAdmin($user);
        } else {
            self::loginCustomer($user);
        }

        return true;
    }

    /**
     * LEGACY METHOD - Generic logout
     */
    public static function logout(): void {
        if (self::isAdmin()) {
            self::logoutAdmin();
        } elseif (self::isCustomer()) {
            self::logoutCustomer();
        }
    }

    /**
     * Require login (redirect if not logged in)
     */
    public static function requireLogin(): void {
        if (!self::check()) {
            redirect('login.php');
        }
    }
}
