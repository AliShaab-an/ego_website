<?php
/**
 * Password Reset Email Template
 * 
 * Variables available:
 * @var string $resetLink - Password reset URL
 * @var string $name - User's name
 */
$siteName = getSetting('website_name', 'EGO');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <!-- Header -->
        <tr>
            <td style="background-color: #000000; padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;"><?= htmlspecialchars($siteName) ?></h1>
            </td>
        </tr>
        <!-- Body -->
        <tr>
            <td style="padding: 40px 30px;">
                <h2 style="color: #333333; margin-top: 0;">Password Reset Request</h2>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                    Hi <?= htmlspecialchars($name ?? 'there') ?>,
                </p>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                    We received a request to reset your password. Click the button below to set a new password:
                </p>
                <p style="text-align: center; margin: 30px 0;">
                    <a href="<?= htmlspecialchars($resetLink) ?>" 
                       style="background-color: #000000; color: #ffffff; padding: 14px 30px; text-decoration: none; border-radius: 5px; font-size: 16px; display: inline-block;">
                        Reset Password
                    </a>
                </p>
                <p style="color: #555555; font-size: 14px; line-height: 1.6;">
                    If you didn't request a password reset, you can safely ignore this email. This link will expire in 1 hour.
                </p>
                <p style="color: #999999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eeeeee; padding-top: 20px;">
                    If the button doesn't work, copy and paste this link into your browser:<br>
                    <a href="<?= htmlspecialchars($resetLink) ?>" style="color: #555555; word-break: break-all;"><?= htmlspecialchars($resetLink) ?></a>
                </p>
            </td>
        </tr>
        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f8f8; padding: 20px 30px; text-align: center;">
                <p style="color: #999999; font-size: 12px; margin: 0;">
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
