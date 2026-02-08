<?php

    class Auth{

        /** Attempt login */
        public static function attempt(string $email, string $password): bool
        {
            $user = User::getByEmail($email);

            if (!$user) {
                return false;
            }

            if (!password_verify($password, $user['password'])) {
                return false;
            }

            self::login($user);
            return true;
        }

        /** Log user into session */
        public static function login(array $user): void
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
        }

        /** Logout user */
        public static function logout(): void
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['username']);
            unset($_SESSION['email']);
            unset($_SESSION['role']);
            session_regenerate_id(true);
        }

        /** Is user logged in? */
        public static function check(): bool
        {
            return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
        }

        /** Get logged-in user array */
        public static function user(): ?array
        {
            if (!self::check()) {
                return null;
            }
            return [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['username'] ?? null,
                'email' => $_SESSION['email'] ?? null,
                'role' => $_SESSION['role'] ?? null,
            ];
        }

        /** Get logged-in user ID */
        public static function id(): ?int
        {
            return $_SESSION['user_id'] ?? null;
        }

        /** Get logged-in user role */
        public static function role(): ?string
        {
            return $_SESSION['role'] ?? null;
        }

        /** Require login (redirect-based, for admin pages) */
        public static function requireLogin(): void
        {
            if (!self::check()) {
                redirect('login.php');
            }
        }
    }
