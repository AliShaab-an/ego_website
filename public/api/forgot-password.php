<?php
require_once __DIR__ . '/../../app/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Please provide a valid email address']);
    exit;
}

try {
    // Check if user exists
    require_once MODELS . 'User.php';
    $user = User::findByEmail($email);

    if (!$user) {
        // Don't reveal if email exists or not for security
        echo json_encode([
            'status' => 'success',
            'message' => 'If an account exists with this email, a password reset link has been sent.'
        ]);
        exit;
    }

    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Store token in database
    DB::query(
        "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?",
        [$token, $expiry, $user['id']]
    );

    // Build reset link
    $resetLink = url("reset-password.php?token=" . urlencode($token));

    // TODO: Send email with reset link
    // For now, we'll just return success
    // In production, you would use a mail service like PHPMailer or SendGrid
    
    // Example email content (to be sent):
    // Subject: Password Reset Request
    // Body: Click this link to reset your password: $resetLink
    // This link expires in 1 hour.

    echo json_encode([
        'status' => 'success',
        'message' => 'If an account exists with this email, a password reset link has been sent.',
        // Remove this in production - only for testing
        'debug_token' => $token,
        'debug_link' => $resetLink
    ]);

} catch (Exception $e) {
    error_log("Forgot password error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred. Please try again later.'
    ]);
}
