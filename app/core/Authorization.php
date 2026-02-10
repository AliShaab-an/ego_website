<?php
/**
 * Authorization Guards with Admin/Customer/Guest Separation
 * Implements role-based access control for the entire application
 */
class Authorization {

    // ============================================================================
    // ROLE CHECKERS
    // ============================================================================

    /**
     * Is current user an admin (admin or super_admin)?
     */
    public static function isAdmin(): bool {
        return Auth::isAdmin();
    }

    /**
     * Is current user a customer?
     */
    public static function isCustomer(): bool {
        return Auth::isCustomer();
    }

    /**
     * Is current user a guest (not logged in)?
     */
    public static function isGuest(): bool {
        return !Auth::check();
    }

    /**
     * Does user have a specific role?
     */
    public static function hasRole(string $role): bool {
        return Auth::check() && Auth::role() === $role;
    }

    /**
     * Does user have any of the specified roles?
     */
    public static function hasAnyRole(array $roles): bool {
        return Auth::check() && in_array(Auth::role(), $roles, true);
    }

    // ============================================================================
    // AUTHORIZATION GUARDS (Enforcement)
    // ============================================================================

    /**
     * DENY ADMIN
     * Block admin and super_admin from accessing this resource
     * Used for: cart, checkout (admins shouldn't shop)
     */
    public static function denyAdmin(): void {
        if (self::isAdmin()) {
            self::deny('Admins cannot access this page');
        }
    }

    /**
     * REQUIRE CUSTOMER
     * Require customer login, block admin and guest
     * Used for: checkout
     */
    public static function requireCustomer(): void {
        if (self::isGuest()) {
            // Guest trying to access - redirect to customer login
            redirect(url('index.php?page=home') . '&login_required=1');
        }
        
        if (self::isAdmin()) {
            // Admin trying to access - forbidden
            self::deny('Admins cannot access this page');
        }
    }

    /**
     * ALLOW GUEST OR CUSTOMER
     * Block only admins, allow guests and customers
     * Used for: cart (guests can use cart, admins cannot)
     */
    public static function allowGuestOrCustomer(): void {
        if (self::isAdmin()) {
            self::deny('Admins cannot access this page');
        }
        // Guests and customers pass through
    }

    /**
     * REQUIRE ADMIN
     * Require admin or super_admin login, block customers and guests
     * Used for: admin panel
     */
    public static function requireAdmin(): void {
        if (!self::isAdmin()) {
            redirect(url('admin/login.php'));
        }
    }

    /**
     * LEGACY: Require specific roles (flexible)
     */
    public static function requireRoles(array $roles): void {
        if (!Auth::check()) {
            self::handleUnauthorized('Authentication required');
        }

        if (!in_array(Auth::role(), $roles, true)) {
            self::deny('Insufficient permissions');
        }
    }

    // ============================================================================
    // INTERNAL HELPERS
    // ============================================================================

    /**
     * Handle unauthorized access (not logged in)
     */
    private static function handleUnauthorized(string $message = 'Authentication required'): void {
        if (self::isApiRequest()) {
            Response::error($message, null, 401);
        } else {
            // Redirect to appropriate login page
            $isAdminPath = strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/') !== false;
            $loginUrl = $isAdminPath ? url('admin/login.php') : url('index.php?page=home&login_required=1');
            redirect($loginUrl);
        }
    }

    /**
     * Deny access (logged in but insufficient permissions)
     */
    private static function deny(string $message = 'Insufficient permissions'): void {
        if (self::isApiRequest()) {
            Response::error($message, null, 403);
        } else {
            // Show 403 error page
            View::render('errors/403', ['pageKey' => '403', 'message' => $message], 'layouts/frontend');
            exit;
        }
    }

    /**
     * Detect if current request is an API request
     */
    private static function isApiRequest(): bool {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        return strpos($requestUri, '/api/') !== false;
    }

    // ============================================================================
    // PERMISSION SYSTEM (Future)
    // ============================================================================

    public static function can(string $permission): bool {
        // Placeholder for future permission-based authorization
        // Example:
        // $permissions = [
        //   'admin' => ['settings.update', 'users.create'],
        //   'super_admin' => ['*'], // all permissions
        //   'customer' => ['orders.view', 'profile.edit']
        // ];
        return false;
    }
}
    