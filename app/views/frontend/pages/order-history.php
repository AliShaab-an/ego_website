<?php
/**
 * Order History Page
 * 
 * Variables available:
 * @var array $orders - List of orders
 * @var array $pagination - Pagination data
 */
$orders = $orders ?? [];
$pagination = $pagination ?? [];
?>

<section class="max-w-4xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold">My Orders</h1>
        <a href="<?= page_url('account') ?>" class="text-sm text-gray-600 hover:text-black">
            <i class="fas fa-arrow-left mr-1"></i> Back to Account
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">You haven't placed any orders yet.</p>
            <a href="<?= page_url('shop') ?>" class="inline-block mt-4 bg-black text-white px-6 py-2 rounded-md hover:bg-gray-800 transition">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $order): 
                $orderNumber = 'ORD-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
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
            <a href="<?= page_url('order-details', ['id' => $order['id']]) ?>" 
               class="block bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="font-semibold text-lg"><?= htmlspecialchars($orderNumber) ?></p>
                        <p class="text-sm text-gray-500"><?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-block px-3 py-1 text-xs font-medium rounded-full <?= $statusClass ?>">
                            <?= htmlspecialchars(ucfirst($order['status'] ?? 'pending')) ?>
                        </span>
                        <span class="inline-block px-3 py-1 text-xs font-medium rounded-full <?= $paymentClass ?>">
                            <?= htmlspecialchars(ucfirst($order['payment_status'] ?? 'pending')) ?>
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">$<?= number_format((float)($order['total'] ?? 0), 2) ?></p>
                        <p class="text-sm text-gray-500"><?= (int)($order['items_count'] ?? 0) ?> item(s)</p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
        <div class="flex justify-center items-center gap-2 mt-8">
            <?php if ($pagination['has_prev'] ?? false): ?>
                <a href="<?= page_url('order-history', ['pg' => $pagination['current_page'] - 1]) ?>" 
                   class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition text-sm">Previous</a>
            <?php endif; ?>
            
            <span class="px-4 py-2 text-sm text-gray-600">
                Page <?= $pagination['current_page'] ?? 1 ?> of <?= $pagination['total_pages'] ?? 1 ?>
            </span>
            
            <?php if ($pagination['has_next'] ?? false): ?>
                <a href="<?= page_url('order-history', ['pg' => $pagination['current_page'] + 1]) ?>" 
                   class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition text-sm">Next</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
