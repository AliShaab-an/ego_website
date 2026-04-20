<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $token    = trim($_POST['token'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm_password'] ?? '');

    if ($token === '' || $password === '' || $confirm === '') {
        throw new Exception('All fields are required');
    }

    if (strlen($password) < 6) {
        throw new Exception('Password must be at least 6 characters');
    }

    if ($password !== $confirm) {
        throw new Exception('Passwords do not match');
    }

    // Find user by valid, non-expired token
    $user = DB::query(
        "SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()",
        [$token]
    )->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('This reset link is invalid or has expired. Please request a new one.');
    }

    // Update password and clear the reset token
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    DB::query(
        "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?",
        [$hashed, $user['id']]
    );

    Response::json([
        'status'  => 'success',
        'message' => 'Password updated successfully'
    ]);
});
