<?php
/**
 * New Order Admin Notification Email Template
 *
 * Variables available:
 * @var array  $order        - Order data (from Order::getById)
 * @var array  $items        - Order items
 * @var string $customerName - Customer name
 * @var string $customerEmail- Customer email
 * @var string $customerPhone- Customer phone
 * @var string $orderNumber  - Order number string (e.g., ORD-000001)
 */
$siteName     = getSetting('website_name', 'EGO');
$orderNumber  = $orderNumber  ?? ('ORD-' . str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT));
$customerName = $customerName ?? $order['customer_name'] ?? 'Customer';
$items        = $items        ?? $order['items'] ?? [];
$totals       = $order['totals'] ?? [];

$paymentLabels = [
    'cod'       => 'Cash on Delivery',
    'bank'      => 'Bank Transfer',
    'omt'       => 'OMT',
    'wishmoney' => 'Wish Money',
];
$paymentLabel = $paymentLabels[$order['payment_method'] ?? ''] ?? ucfirst($order['payment_method'] ?? 'N/A');

$shippingAddress = trim(implode(', ', array_filter([
    $order['address']  ?? '',
    $order['city']     ?? '',
    $order['state']    ?? '',
    $order['zip']      ?? '',
])));
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Order <?= htmlspecialchars($orderNumber) ?></title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0;">

        <!-- Header -->
        <tr>
            <td style="background-color: #111111; padding: 24px 30px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 600;"><?= htmlspecialchars($siteName) ?></h1>
                            <p style="color: #aaaaaa; margin: 4px 0 0; font-size: 13px;">Admin Order Notification</p>
                        </td>
                        <td style="text-align: right;">
                            <span style="background-color: #28a745; color: #ffffff; padding: 6px 14px; border-radius: 4px; font-size: 13px; font-weight: bold;">NEW ORDER</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Order Number Banner -->
        <tr>
            <td style="background-color: #f8f8f8; padding: 16px 30px; border-bottom: 1px solid #eeeeee;">
                <p style="margin: 0; font-size: 18px; font-weight: bold; color: #333;">
                    <?= htmlspecialchars($orderNumber) ?>
                    <span style="font-size: 13px; color: #888; font-weight: normal; margin-left: 10px;">
                        <?= date('M j, Y \a\t g:i A', strtotime($order['created_at'] ?? 'now')) ?>
                    </span>
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding: 30px;">

                <!-- Two-column: customer + order info -->
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 25px;">
                    <tr valign="top">
                        <!-- Customer Info -->
                        <td width="50%" style="padding-right: 12px;">
                            <h3 style="margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: #999;">Customer</h3>
                            <table cellpadding="4" cellspacing="0" style="border: 1px solid #eeeeee; border-radius: 5px; width: 100%;">
                                <tr>
                                    <td style="font-size: 14px; color: #333; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;">
                                        <strong><?= htmlspecialchars($customerName) ?></strong>
                                    </td>
                                </tr>
                                <?php if (!empty($customerEmail)): ?>
                                <tr>
                                    <td style="font-size: 13px; color: #555; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;">
                                        <?= htmlspecialchars($customerEmail) ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($customerPhone)): ?>
                                <tr>
                                    <td style="font-size: 13px; color: #555; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;">
                                        <?= htmlspecialchars($customerPhone) ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($shippingAddress)): ?>
                                <tr>
                                    <td style="font-size: 13px; color: #555; padding: 8px 12px;">
                                        <?= htmlspecialchars($shippingAddress) ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </td>

                        <!-- Order Info -->
                        <td width="50%" style="padding-left: 12px;">
                            <h3 style="margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: #999;">Order Details</h3>
                            <table cellpadding="4" cellspacing="0" style="border: 1px solid #eeeeee; border-radius: 5px; width: 100%;">
                                <tr>
                                    <td style="font-size: 13px; color: #777; padding: 8px 12px; border-bottom: 1px solid #f0f0f0; width: 45%;">Payment</td>
                                    <td style="font-size: 13px; color: #333; font-weight: bold; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars($paymentLabel) ?></td>
                                </tr>
                                <tr style="background-color: #f9f9f9;">
                                    <td style="font-size: 13px; color: #777; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;">Status</td>
                                    <td style="font-size: 13px; color: #333; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars(ucfirst($order['status'] ?? 'pending')) ?></td>
                                </tr>
                                <?php if (!empty($order['shipping_region'])): ?>
                                <tr>
                                    <td style="font-size: 13px; color: #777; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;">Region</td>
                                    <td style="font-size: 13px; color: #333; padding: 8px 12px; border-bottom: 1px solid #f0f0f0;"><?= htmlspecialchars($order['shipping_region']) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (!empty($order['notes'])): ?>
                                <tr>
                                    <td style="font-size: 13px; color: #777; padding: 8px 12px;">Notes</td>
                                    <td style="font-size: 13px; color: #555; padding: 8px 12px; font-style: italic;"><?= htmlspecialchars($order['notes']) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Items -->
                <?php if (!empty($items)): ?>
                <h3 style="margin: 0 0 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: #999;">Items</h3>
                <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #eeeeee; border-radius: 5px; border-collapse: collapse; margin-bottom: 20px;">
                    <thead>
                        <tr style="background-color: #f8f8f8;">
                            <th style="text-align: left; padding: 10px 12px; font-size: 13px; color: #555; border-bottom: 1px solid #eeeeee; font-weight: 600;">Product</th>
                            <th style="text-align: center; padding: 10px 12px; font-size: 13px; color: #555; border-bottom: 1px solid #eeeeee; font-weight: 600; white-space: nowrap;">Variant</th>
                            <th style="text-align: center; padding: 10px 12px; font-size: 13px; color: #555; border-bottom: 1px solid #eeeeee; font-weight: 600;">Qty</th>
                            <th style="text-align: right; padding: 10px 12px; font-size: 13px; color: #555; border-bottom: 1px solid #eeeeee; font-weight: 600;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $i => $item): ?>
                        <tr <?= $i % 2 === 1 ? 'style="background-color: #fafafa;"' : '' ?>>
                            <td style="padding: 10px 12px; font-size: 14px; color: #333; border-bottom: 1px solid #f0f0f0;">
                                <?= htmlspecialchars($item['product_name'] ?? 'Product') ?>
                            </td>
                            <td style="padding: 10px 12px; font-size: 13px; color: #777; text-align: center; border-bottom: 1px solid #f0f0f0; white-space: nowrap;">
                                <?= htmlspecialchars(trim(implode(' / ', array_filter([
                                    $item['color_name'] ?? '',
                                    $item['size_name']  ?? '',
                                ])))) ?: '—' ?>
                            </td>
                            <td style="padding: 10px 12px; font-size: 14px; color: #333; text-align: center; border-bottom: 1px solid #f0f0f0;">
                                <?= (int)($item['quantity'] ?? 0) ?>
                            </td>
                            <td style="padding: 10px 12px; font-size: 14px; color: #333; text-align: right; border-bottom: 1px solid #f0f0f0;">
                                $<?= number_format((float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1), 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>

                <!-- Totals -->
                <?php if (!empty($totals)): ?>
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 260px; margin-left: auto;">
                    <?php if (isset($totals['subtotal'])): ?>
                    <tr>
                        <td style="padding: 5px 0; font-size: 13px; color: #777;">Subtotal</td>
                        <td style="padding: 5px 0; font-size: 13px; color: #333; text-align: right;">$<?= number_format((float)$totals['subtotal'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($totals['shipping'])): ?>
                    <tr>
                        <td style="padding: 5px 0; font-size: 13px; color: #777;">Shipping</td>
                        <td style="padding: 5px 0; font-size: 13px; color: #333; text-align: right;">$<?= number_format((float)$totals['shipping'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($totals['discount'])): ?>
                    <tr>
                        <td style="padding: 5px 0; font-size: 13px; color: #777;">Discount</td>
                        <td style="padding: 5px 0; font-size: 13px; color: #28a745; text-align: right;">-$<?= number_format((float)$totals['discount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (isset($totals['grand_total'])): ?>
                    <tr>
                        <td style="padding: 10px 0 5px; font-size: 15px; font-weight: bold; color: #111; border-top: 2px solid #333;">Total</td>
                        <td style="padding: 10px 0 5px; font-size: 15px; font-weight: bold; color: #111; text-align: right; border-top: 2px solid #333;">$<?= number_format((float)$totals['grand_total'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
                <?php endif; ?>

            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8f8f8; padding: 16px 30px; text-align: center; border-top: 1px solid #eeeeee;">
                <p style="color: #999999; font-size: 12px; margin: 0;">
                    &copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?>. This is an automated admin notification.
                </p>
            </td>
        </tr>

    </table>
</body>
</html>
