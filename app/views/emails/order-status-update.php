<?php
/**
 * Order Status Update Email Template
 * 
 * Variables available:
 * @var array $order - Order data
 * @var string $customerName - Customer name
 * @var string $orderNumber - Order number string
 * @var string $newStatus - New order status
 */
$siteName = getSetting('website_name', 'EGO');
$orderNumber = $orderNumber ?? ('ORD-' . str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT));
$customerName = $customerName ?? $order['customer_name'] ?? 'Customer';

$statusMessages = [
    'pending'   => 'Your order is being processed.',
    'shipped'   => 'Great news! Your order has been shipped and is on its way.',
    'completed' => 'Your order has been delivered. We hope you enjoy your purchase!',
    'cancelled' => 'Your order has been cancelled. If you did not request this, please contact us.',
];
$statusMessage = $statusMessages[$newStatus ?? ''] ?? 'Your order status has been updated.';

$statusColors = [
    'pending'   => '#f0ad4e',
    'shipped'   => '#5bc0de',
    'completed' => '#5cb85c',
    'cancelled' => '#d9534f',
];
$statusColor = $statusColors[$newStatus ?? ''] ?? '#555555';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Update</title>
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
                <h2 style="color: #333333; margin-top: 0;">Order Status Update</h2>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                    Hi <?= htmlspecialchars($customerName) ?>,
                </p>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                    <?= htmlspecialchars($statusMessage) ?>
                </p>

                <table width="100%" cellpadding="12" cellspacing="0" style="margin: 25px 0; border: 1px solid #eeeeee; border-radius: 5px;">
                    <tr>
                        <td style="font-weight: bold; color: #333; width: 50%;">Order Number</td>
                        <td style="color: #555;"><?= htmlspecialchars($orderNumber) ?></td>
                    </tr>
                    <tr style="background-color: #f8f8f8;">
                        <td style="font-weight: bold; color: #333;">New Status</td>
                        <td>
                            <span style="background-color: <?= $statusColor ?>; color: #ffffff; padding: 4px 12px; border-radius: 3px; font-size: 14px;">
                                <?= htmlspecialchars(ucfirst($newStatus ?? 'Unknown')) ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <p style="color: #555555; font-size: 14px; line-height: 1.6;">
                    If you have any questions about your order, please contact us.
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
