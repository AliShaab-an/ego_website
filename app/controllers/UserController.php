<?php
class UserController
{

    public function listUsers()
    {
        $customers = User::countAll();
        return ['status' => 'success', 'data' => $customers];
    }

    public function listCustomersCountLast7Days()
    {
        $customersCount = User::getCustomersCountLast7Days();
        return ['status' => 'success', 'data' => $customersCount];
    }

    public function listAdmins()
    {
        $admins = User::getAllAdmins();
        return ['status' => 'success', 'data' => $admins];
    }

    public function register()
    {
        require_once __DIR__ . '/CartController.php';

        $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');
        $password = trim($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'customer';

        if ($name === '' || $email === '' || $password === '') {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }
        $existingUser = User::getByEmail($email);
        if ($existingUser) {
            return ['status' => 'error', 'message' => 'Email already registered'];
        }

        $userId = User::createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);

        // Log customer into session using Auth
        Auth::loginCustomer([
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);

        // Migrate guest cart to user if exists
        $cartController = new CartController();
        $cartController->migrateSessionCartToUser($userId);

        return ['status' => 'success', 'id' => $userId, 'message' => 'User registered successfully'];
    }

    public function login()
    {
        require_once __DIR__ . '/CartController.php';

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($email === '' || $password === '') {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }

        // Attempt customer login (only customers allowed on frontend)
        if (!Auth::attemptCustomer($email, $password)) {
            return ['status' => 'error', 'message' => 'Invalid email or password'];
        }

        $user = Auth::user();
        
        // Migrate guest cart to user if exists
        $cartController = new CartController();
        $cartController->migrateSessionCartToUser($user['id']);

        return ['status' => 'success', 'id' => $user['id'], 'message' => 'Login successful'];
    }

    public function addAdmin()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'admin';

        if ($name === '' || $email === '' || $password === '') {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email format.'];
        }
        $allowedRoles = ['admin', 'editor'];
        if (!in_array($role, $allowedRoles, true)) {
            return ['status' => 'error', 'message' => 'Invalid role.'];
        }
        // Check if email already exists
        $existing = User::getAdminByEmail($email);
        if ($existing) {
            return ['status' => 'error', 'message' => 'An admin with this email already exists.'];
        }

        $id = User::createAdmin($name, $email, $password, $role);
        return ['status' => 'success', 'id' => $id, 'message' => 'Admin added successfully.'];
    }

    public function updateAdmin()
    {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($id <= 0 || $name === '' || $email === '') {
            return ['status' => 'error', 'message' => 'Missing or invalid data.'];
        }

        User::updateAdmin($id, $name, $email, $role, $password ?: null);
        return ['status' => 'success', 'message' => 'Admin updated successfully.'];
    }

    public function deleteAdmin()
    {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            return ['status' => 'error', 'message' => 'Invalid admin ID.'];
        }

        User::deleteAdmin($id);
        return ['status' => 'success', 'message' => 'Admin deleted successfully.'];
    }

    public function logout()
    {
        // Customer logout - preserves cart session
        Auth::logoutCustomer();

        return ['status' => 'success', 'message' => 'Logged out successfully'];
    }

    public function adminLogin()
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }

        // Attempt admin login (only admin/super_admin allowed)
        if (!Auth::attemptAdmin($email, $password)) {
            return ['status' => 'error', 'message' => 'Invalid credentials or insufficient permissions'];
        }

        return ['status' => 'success', 'message' => 'Login successful', 'redirect' => 'index.php?action=dashboard'];
    }
}
