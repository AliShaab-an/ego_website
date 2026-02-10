<?php 
/**
 * Middleware - Request Guards
 * Used primarily for admin panel authentication
 */
class Middleware {

    /**
     * Require specific roles (admin panel usage)
     * Redirects to admin login if not authenticated or insufficient role
     */
    public static function requireRoles(array $roles): void {
        if (!Auth::check() || !in_array(Auth::role(), $roles, true)) {
            redirect('login.php');
        }
    }

    /**
     * Require admin role specifically
     * Alias for Authorization::requireAdmin() for backward compatibility
     */
    public static function requireAdmin(): void {
        Authorization::requireAdmin();
    }
}