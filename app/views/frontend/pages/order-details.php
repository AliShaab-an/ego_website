<?php
/**
 * Order Details Page
 * 
 * Variables available:
 * @var array $order - Full order data from Order::getById()
 */
$order = $order ?? [];
$items = $order['items'] ?? [];
$totals = $order['totals'] ?? [];
$orderNumber = 'ORD-' . str_pad($order['id'] ?? 0, 6, '0', STR_PAD_LEFT);

$statusColors = [
    'pending' => 'bg-yellow-100 text-yellow-800',
    'shipped' => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-green-100 text-green-800',
    'cancelled' => 'bg-red-100 text-red-800',
];
$statusClass = $statusColors[$order['status'] ?? 'pending'] ?? 'bg-gray-100 text-gray-800';
$paymentStatusColors = [
    'pending' => 'bg-yellow-100 text-yellow-800',
    'paid' => 'bg-green-100 text-green-800',
    'failed' => 'bg-red-100 text-red-800',
];
$paymentClass = $paymentStatusColors[$order['payment_status'] ?? 'pending'] ?? 'bg-gray-100 text-gray-800';
?>

<section class="max-w-4xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold"><?= htmlspecialchars($orderNumber) ?></h1>
        <a href="<?= page_url('order-history') ?>" class="text-sm text-gray-600 hover:text-black">
            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>

    <?php if (empty($order)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">Order not found.</p>
        </div>
    <?php else: ?>

    <!-- Order Summary -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-medium"><?= date('F j, Y', strtotime($order['created_at'] ?? 'now')) ?></p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                    <?= htmlspecialchars(ucfirst($order['status'] ?? 'pending')) ?>
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500">Payment</p>
                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full <?= $paymentClass ?>">
                    <?= htmlspecialchars(ucfirst($order['payment_status'] ?? 'pending')) ?>
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500">Payment Method</p>
                <p class="font-medium"><?= htmlspecialchars(ucfirst($order['payment_method'] ?? 'N/A')) ?></p>
            </div>
        </div>
    </div>

    <!-- Shipping Info -->
    <?php if (!empty($order['address']) || !empty($order['region_name'])): ?>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
        <div class="text-gray-700">
            <p class="font-medium"><?= htmlspecialchars($order['customer_name'] ?? '') ?></p>
            <?php if (!empty($order['customer_email'])): ?>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($order['customer_email']) ?></p>
            <?php endif; ?>
            <?php if (!empty($order['customer_phone'])): ?>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($order['customer_phone']) ?></p>
            <?php endif; ?>
            <?php if (!empty($order['address'])): ?>
                <p class="mt-2"><?= htmlspecialchars($order['address']) ?></p>
            <?php endif; ?>
            <?php if (!empty($order['region_name'])): ?>
                <p class="text-sm text-gray-500">Region: <?= htmlspecialchars($order['region_name']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Items</h2>
        <?php if (!empty($items)): ?>
        <div class="space-y-4">
            <?php foreach ($items as $item): ?>
            <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
                <?php if (!empty($item['image_path'])): ?>
                <img src="<?= url($item['image_path']) ?>" alt="<?= htmlspecialchars($item['product_name'] ?? '') ?>" 
                     class="w-16 h-16 object-cover rounded">
                <?php else: ?>
                <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                    <i class="fas fa-image text-gray-300"></i>
                </div>
                <?php endif; ?>
                <div class="flex-1">
                    <p class="font-medium"><?= htmlspecialchars($item['product_name'] ?? 'Product') ?></p>
                    <?php if (!empty($item['color_name']) || !empty($item['size_name'])): ?>
                    <p class="text-sm text-gray-500">
                        <?= !empty($item['color_name']) ? htmlspecialchars($item['color_name']) : '' ?>
                        <?= !empty($item['size_name']) ? ' / ' . htmlspecialchars($item['size_name']) : '' ?>
                    </p>
                    <?php endif; ?>
                    <p class="text-sm text-gray-500">Qty: <?= (int)($item['quantity'] ?? 0) ?></p>
                </div>
                <div class="text-right">
                    <p class="font-medium">$<?= number_format((float)($item['price'] ?? 0), 2) ?></p>
                    <?php if (!empty($item['discount']) && (float)$item['discount'] > 0): ?>
                    <p class="text-sm text-green-600">-<?= number_format((float)$item['discount'], 2) ?>% off</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-gray-500">No items found for this order.</p>
        <?php endif; ?>
    </div>

    <!-- Totals -->
    <?php if (!empty($totals)): ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Order Total</h2>
        <div class="space-y-2">
            <?php if (isset($totals['subtotal'])): ?>
            <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span>$<?= number_format((float)$totals['subtotal'], 2) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($totals['shipping'])): ?>
            <div class="flex justify-between">
                <span class="text-gray-600">Shipping</span>
                <span>$<?= number_format((float)$totals['shipping'], 2) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($totals['discount'])): ?>
            <div class="flex justify-between text-green-600">
                <span>Discount<?= !empty($order['coupon_code']) ? ' (' . htmlspecialchars($order['coupon_code']) . ')' : '' ?></span>
                <span>-$<?= number_format((float)$totals['discount'], 2) ?></span>
            </div>
            <?php endif; ?>
            <?php if (isset($totals['grand_total'])): ?>
            <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                <span>Total</span>
                <span>$<?= number_format((float)$totals['grand_total'], 2) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</section>
