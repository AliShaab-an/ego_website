<?php
/**
 * Order Confirmation Email Template
 * 
 * Variables available:
 * @var array $order - Order data (from Order::getById)
 * @var array $items - Order items
 * @var string $customerName - Customer name
 * @var string $orderNumber - Order number string (e.g., ORD-000001)
 */
$siteName = getSetting('website_name', 'EGO');
$orderNumber = $orderNumber ?? ('ORD-' . str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT));
$customerName = $customerName ?? $order['customer_name'] ?? 'Customer';
$items = $items ?? $order['items'] ?? [];
$totals = $order['totals'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
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
                <h2 style="color: #333333; margin-top: 0;">Order Confirmation</h2>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                    Hi <?= htmlspecialchars($customerName) ?>,
                </p>
                <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                    Thank you for your order! Here's a summary of your purchase:
                </p>

                <!-- Order Info -->
                <table width="100%" cellpadding="8" cellspacing="0" style="margin: 20px 0; border: 1px solid #eeeeee; border-radius: 5px;">
                    <tr style="background-color: #f8f8f8;">
                        <td style="font-weight: bold; color: #333;">Order Number</td>
                        <td style="color: #555;"><?= htmlspecialchars($orderNumber) ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #333;">Date</td>
                        <td style="color: #555;"><?= date('F j, Y', strtotime($order['created_at'] ?? 'now')) ?></td>
                    </tr>
                    <tr style="background-color: #f8f8f8;">
                        <td style="font-weight: bold; color: #333;">Payment Method</td>
                        <td style="color: #555;"><?= htmlspecialchars(ucfirst($order['payment_method'] ?? 'N/A')) ?></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #333;">Status</td>
                        <td style="color: #555;"><?= htmlspecialchars(ucfirst($order['status'] ?? 'pending')) ?></td>
                    </tr>
                </table>

                <!-- Order Items -->
                <?php if (!empty($items)): ?>
                <h3 style="color: #333333; margin-bottom: 10px;">Items Ordered</h3>
                <table width="100%" cellpadding="8" cellspacing="0" style="border: 1px solid #eeeeee; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f8f8;">
                            <th style="text-align: left; border-bottom: 1px solid #eee; color: #333;">Item</th>
                            <th style="text-align: center; border-bottom: 1px solid #eee; color: #333;">Qty</th>
                            <th style="text-align: right; border-bottom: 1px solid #eee; color: #333;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="border-bottom: 1px solid #eee; color: #555;">
                                <?= htmlspecialchars($item['product_name'] ?? 'Product') ?>
                                <?php if (!empty($item['color_name']) || !empty($item['size_name'])): ?>
                                    <br><small style="color: #999;">
                                        <?= !empty($item['color_name']) ? htmlspecialchars($item['color_name']) : '' ?>
                                        <?= !empty($item['size_name']) ? ' / ' . htmlspecialchars($item['size_name']) : '' ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td style="border-bottom: 1px solid #eee; color: #555; text-align: center;"><?= (int)($item['quantity'] ?? 0) ?></td>
                            <td style="border-bottom: 1px solid #eee; color: #555; text-align: right;">$<?= number_format((float)($item['price'] ?? 0), 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <!-- Totals -->
                <?php if (!empty($totals)): ?>
                <table width="100%" cellpadding="5" cellspacing="0" style="margin-top: 15px;">
                    <?php if (isset($totals['subtotal'])): ?>
                    <tr>
                        <td style="text-align: right; color: #555;">Subtotal:</td>
                        <td style="text-align: right; color: #555; width: 100px;">$<?= number_format((float)$totals['subtotal'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($totals['shipping'])): ?>
                    <tr>
                        <td style="text-align: right; color: #555;">Shipping:</td>
                        <td style="text-align: right; color: #555;">$<?= number_format((float)$totals['shipping'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($totals['discount'])): ?>
                    <tr>
                        <td style="text-align: right; color: #555;">Discount:</td>
                        <td style="text-align: right; color: #28a745;">-$<?= number_format((float)$totals['discount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (isset($totals['grand_total'])): ?>
                    <tr>
                        <td style="text-align: right; font-weight: bold; color: #333; border-top: 2px solid #eee; padding-top: 10px;">Total:</td>
                        <td style="text-align: right; font-weight: bold; color: #333; border-top: 2px solid #eee; padding-top: 10px;">$<?= number_format((float)$totals['grand_total'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
                <?php endif; ?>

                <p style="color: #555555; font-size: 14px; line-height: 1.6; margin-top: 30px;">
                    We'll notify you when your order status changes. If you have any questions, please don't hesitate to contact us.
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
