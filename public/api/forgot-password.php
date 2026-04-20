<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        throw new Exception('Please provide a valid email address');
    }

    $user = User::getByEmail($email);

    // Don't reveal whether the email exists (security)
    if (!$user) {
        Response::json([
            'status'  => 'success',
            'message' => 'If an account exists with this email, a password reset link has been sent.'
        ]);
        return;
    }

    // Generate reset token — use MySQL NOW() so expiry is in the same timezone
    $token = bin2hex(random_bytes(32));

    DB::query(
        "UPDATE users SET reset_token = ?, reset_token_expiry = NOW() + INTERVAL 1 HOUR WHERE id = ?",
        [$token, $user['id']]
    );

    // Send password reset email (silently fails if SMTP is disabled)
    try {
        $resetLink = url('reset-password.php?token=' . urlencode($token));
        $htmlBody  = EmailService::renderTemplate('password-reset', [
            'resetLink' => $resetLink,
            'name'      => $user['name'] ?? ''
        ]);
        EmailService::send($user['email'], 'Password Reset Request', $htmlBody);
    } catch (Exception $emailErr) {
        Logger::error('Password reset email failed', $emailErr->getMessage());
    }

    Response::json([
        'status'  => 'success',
        'message' => 'If an account exists with this email, a password reset link has been sent.'
    ]);
});
