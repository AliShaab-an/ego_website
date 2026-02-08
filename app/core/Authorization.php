<?php 

    class Authorization{

        public static function requireRoles(array $roles): void
        {
            if (!Auth::check()) {
                self::handleUnauthorized('Authentication required');
            }

            if (!in_array(Auth::role(), $roles, true)) {
                self::deny();
            }
        }

        public static function hasRole(string $role): bool
        {
            return Auth::check() && Auth::role() === $role;
        }

        public static function hasAnyRole(array $roles): bool
        {
            return Auth::check() && in_array(Auth::role(), $roles, true);
        }

        /** Permission system placeholder (future) */
        public static function can(string $permission): bool
        {
            // Later: map roles → permissions
            // Example:
            // $permissions = [
            //   'admin' => ['settings.update','users.create'],
            //   'editor' => ['products.edit']
            // ];
            return false;
        }

        private static function handleUnauthorized(string $message = 'Authentication required'): void
        {
            if (self::isApiRequest()) {
                Response::error($message, null, 401);
            } else {
                redirect('login.php');
            }
        }

        private static function deny(): void
        {
            if (self::isApiRequest()) {
                Response::error('Unauthorized - Insufficient permissions', null, 403);
            } else {
                redirect('index.php?action=dashboard&error=unauthorized');
            }
        }

        private static function isApiRequest(): bool
        {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            return strpos($requestUri, '/api/') !== false;
        }
    }
    