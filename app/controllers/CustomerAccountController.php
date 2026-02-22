<?php

/**
 * CustomerAccountController
 * 
 * Handles customer account features:
 * - Order history
 * - Order details
 * - Profile update
 * - Password change
 */
class CustomerAccountController
{
    /**
     * Get paginated order history for the logged-in customer
     * 
     * @return array
     */
    public function getOrderHistory(): array
    {
        $userId = Auth::id();
        
        $page = isset($_GET['pg']) ? max(1, (int)$_GET['pg']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $orders = Order::getUserOrders($userId, $limit, $offset);
        $totalCount = Order::countAll('all', $userId);
        $totalPages = ceil($totalCount / $limit);
        
        return [
            'orders' => $orders,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_count' => $totalCount,
                'per_page' => $limit,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
    }
    
    /**
     * Get details for a specific order (must belong to logged-in customer)
     * 
     * @param int $orderId
     * @return array
     * @throws Exception
     */
    public function getOrderDetails(int $orderId): array
    {
        $order = Order::getById($orderId);
        
        if (!$order) {
            throw new Exception('Order not found');
        }
        
        // Ensure the order belongs to the logged-in customer
        if ((int)$order['user_id'] !== Auth::id()) {
            throw new Exception('Unauthorized access');
        }
        
        return $order;
    }
    
    /**
     * Update customer profile (name, email)
     * 
     * @return array Response
     */
    public function updateProfile(): array
    {
        $userId = Auth::id();
        
        $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $state = trim($_POST['state'] ?? '');
        $zip = trim($_POST['zip'] ?? '');
        
        if ($name === '' || $email === '') {
            return ['status' => 'error', 'message' => 'Name and email are required'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email address'];
        }
        
        // Check if email already taken by another user
        $existingUser = User::getByEmail($email);
        if ($existingUser && (int)$existingUser['id'] !== $userId) {
            return ['status' => 'error', 'message' => 'Email is already in use by another account'];
        }
        
        // Update basic info
        User::updateUser($userId, [
            'name' => $name,
            'email' => $email,
            'role' => 'customer' // Preserve role
        ]);
        
        // Update contact info
        User::updateContactInfo($userId, $phone, $address, $city, $state, $zip);
        
        // Update session data
        Session::setInNamespace('customer', 'name', $name);
        Session::setInNamespace('customer', 'email', $email);
        
        return ['status' => 'success', 'message' => 'Profile updated successfully'];
    }
    
    /**
     * Change customer password
     * 
     * @return array Response
     */
    public function changePassword(): array
    {
        $userId = Auth::id();
        
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        
        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            return ['status' => 'error', 'message' => 'All password fields are required'];
        }
        
        if ($newPassword !== $confirmPassword) {
            return ['status' => 'error', 'message' => 'New passwords do not match'];
        }
        
        if (strlen($newPassword) < 6) {
            return ['status' => 'error', 'message' => 'New password must be at least 6 characters'];
        }
        
        // Verify current password
        $user = User::findById($userId);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return ['status' => 'error', 'message' => 'Current password is incorrect'];
        }
        
        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        DB::query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $userId]);
        
        return ['status' => 'success', 'message' => 'Password changed successfully'];
    }
}
