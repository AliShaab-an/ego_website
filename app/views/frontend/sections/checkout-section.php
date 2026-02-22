<?php
// Cart and user data passed from FrontendController
$cartItems = $cartItems ?? [];
$cartTotal = $cartTotal ?? 0;
$cartCount = $cartCount ?? 0;
$userName = $userName ?? '';
$userEmail = $userEmail ?? '';
$userPhone = $userPhone ?? '';

// Ensure payment methods have a default (COD at minimum)
if (!isset($paymentMethods) || empty($paymentMethods)) {
    $paymentMethods = [
        'cod' => [
            'enabled' => true,
            'label' => 'Cash on Delivery',
            'instructions' => '',
            'requires_proof' => false
        ]
    ];
}
?>

<section class="max-w-7xl mx-auto px-4 py-10">
    <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center font-cor">Checkout</h1>

    <?php if (empty($cartItems)): ?>
    <!-- Empty Cart -->
    <div class="text-center py-16">
        <i class="fi fi-rr-shopping-cart text-6xl text-gray-300 mb-4"></i>
        <h2 class="text-2xl font-semibold text-gray-600 mb-2">Your cart is empty</h2>
        <p class="text-gray-500 mb-6">Add some products to checkout!</p>
        <a href="<?= page_url('shop') ?>" class="bg-brand text-white px-8 py-3 rounded-lg hover:bg-brand-dark transition-colors">
            Continue Shopping
        </a>
    </div>
    <?php else: ?>

    <div class="flex flex-col gap-8">
        <!-- Payment Method Selection -->
        <div class="space-y-4 py-4 border-b">
            <h2 class="text-2xl md:text-3xl">Payment Method</h2>
            <?php 
            $enabledPayments = array_filter($paymentMethods ?? [], function($pm) {
                return $pm['enabled'] ?? false;
            });
            
            if (empty($enabledPayments)): 
            ?>
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded">
                    <p class="font-semibold">No payment methods available</p>
                    <p class="text-sm mt-1">Please contact support to complete your order.</p>
                </div>
            <?php else: ?>
                <div class="flex flex-wrap gap-3">
                    <?php foreach ($paymentMethods as $key => $method): ?>
                        <?php if ($method['enabled']): ?>
                            <button type="button" 
                                    class="payment-method-btn flex-1 min-w-[120px] border-2 px-4 py-2 md:px-8 md:py-3 text-lg md:text-xl hover:border-brand hover:text-brand transition-colors rounded" 
                                    data-method="<?= htmlspecialchars($key) ?>"
                                    data-requires-proof="<?= !empty($method['requires_proof']) ? 'true' : 'false' ?>">
                                <?= htmlspecialchars($method['label']) ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <p id="payment-method-error" class="text-red-500 text-sm hidden">Please select a payment method</p>
                
                <!-- Payment Instructions -->
                <?php foreach ($paymentMethods as $key => $method): ?>
                    <?php if ($method['enabled'] && !empty($method['instructions'])): ?>
                        <div id="payment-instructions-<?= htmlspecialchars($key) ?>" class="payment-instructions hidden bg-blue-50 border border-blue-200 p-4 rounded mt-3">
                            <p class="text-sm text-gray-700 whitespace-pre-line"><?= htmlspecialchars($method['instructions']) ?></p>
                            <?php if ($key === 'wishmoney' && !empty($method['number'])): ?>
                                <p class="text-sm mt-2">
                                    <strong>Wish Money Number:</strong> <?= htmlspecialchars($method['number']) ?><br>
                                    <?php if (!empty($method['name'])): ?>
                                        <strong>Name:</strong> <?= htmlspecialchars($method['name']) ?>
                                    <?php endif; ?>
                                </p>
                            <?php elseif ($key === 'bank' && !empty($method['account'])): ?>
                                <p class="text-sm mt-2">
                                    <?php if (!empty($method['bank_name'])): ?>
                                        <strong>Bank:</strong> <?= htmlspecialchars($method['bank_name']) ?><br>
                                    <?php endif; ?>
                                    <strong>Account Number:</strong> <?= htmlspecialchars($method['account']) ?><br>
                                    <?php if (!empty($method['account_name'])): ?>
                                        <strong>Account Name:</strong> <?= htmlspecialchars($method['account_name']) ?>
                                    <?php endif; ?>
                                </p>
                            <?php elseif ($key === 'omt' && !empty($method['name'])): ?>
                                <p class="text-sm mt-2">
                                    <strong>Recipient Name:</strong> <?= htmlspecialchars($method['name']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="flex flex-col md:flex-row md:justify-between gap-10">

            <!-- Shipping Info -->
            <form id="checkout-form" class="flex-1 flex flex-col gap-3">
                <h2 class="text-2xl md:text-3xl">Shipping Information</h2>

                <input type="hidden" id="selected-payment-method" name="payment_method" value="">

                <label class="text-lg" for="customer-name">Name <span class="text-red-500">*</span></label>
                <input class="border w-full h-12 p-2 outline-none focus:border-brand" type="text" id="customer-name" name="name" placeholder="Enter your name" value="<?= htmlspecialchars($userName) ?>" required>

                <label class="text-lg" for="customer-phone">Phone Number <span class="text-red-500">*</span></label>
                <input class="border w-full h-12 p-2 outline-none focus:border-brand" id="customer-phone" name="phone" placeholder="+961..." value="<?= htmlspecialchars($userPhone) ?>" required>

                <!-- Cash on Delivery Fields (Hidden by default) -->
                <div id="cod-fields" class="hidden space-y-3">
                    <label class="text-lg" for="customer-email">Email <span class="text-red-500">*</span></label>
                    <input class="border w-full h-12 p-2 outline-none focus:border-brand" type="email" id="customer-email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($userEmail) ?>">

                    <label class="text-lg" for="customer-address">Address <span class="text-red-500">*</span></label>
                    <textarea class="border w-full h-24 p-2 outline-none focus:border-brand resize-none" id="customer-address" name="address" placeholder="Enter your full address"></textarea>

                    <!-- City, State, Zip -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="flex flex-col">
                            <label class="text-lg" for="customer-city">City <span class="text-red-500">*</span></label>
                            <input class="border h-10 p-2 outline-none focus:border-brand" type="text" id="customer-city" name="city" placeholder="Enter City">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-lg" for="customer-state">State</label>
                            <input class="border h-10 p-2 outline-none focus:border-brand" type="text" id="customer-state" name="state" placeholder="Enter State">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-lg" for="customer-zip">Zip Code</label>
                            <input class="border h-10 p-2 outline-none focus:border-brand" type="text" id="customer-zip" name="zip" placeholder="Enter Code">
                        </div>
                    </div>

                    <label class="text-lg" for="order-notes">Order Notes (Optional)</label>
                    <textarea class="border w-full h-20 p-2 outline-none focus:border-brand resize-none" id="order-notes" name="notes" placeholder="Any special instructions?"></textarea>
                    
                    <!-- Payment Proof Upload (for non-COD methods that require it) -->
                    <div id="payment-proof-section" class="hidden space-y-2">
                        <label class="text-lg" for="payment-proof">Payment Proof <span class="text-red-500">*</span></label>
                        <input type="file" 
                               id="payment-proof" 
                               name="payment_proof" 
                               accept="image/*,.pdf"
                               class="border w-full p-2 outline-none focus:border-brand">
                        <p class="text-sm text-gray-600">Please upload a screenshot or photo of your payment receipt/transfer confirmation</p>
                    </div>
                </div>
            </form>

            <!-- Cart Review -->
            <div class="flex-1 flex flex-col items-start py-4 gap-4 border-t md:border-t-0">
                <h3 class="text-xl font-semibold">Review Your Cart</h3>

                <!-- Cart Items -->
                <div id="checkout-cart-items" class="w-full space-y-3 max-h-96 overflow-y-auto">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="flex gap-4 items-center border-b pb-3">
                        <img src="<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'assets/images/placeholder.png' ?>" 
                             alt="<?= htmlspecialchars($item['name'] ?? 'Product') ?>" 
                             class="w-16 h-20 object-cover rounded">
                        <div class="flex-1">
                            <p class="text-lg font-outfit"><?= htmlspecialchars($item['name'] ?? 'Product') ?></p>
                            <p class="text-gray-500 text-sm">
                                <?= htmlspecialchars($item['size'] ?? 'N/A') ?> / <?= htmlspecialchars($item['color'] ?? 'N/A') ?>
                            </p>
                            <p class="text-sm text-gray-600">Qty: <?= (int)$item['quantity'] ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">$<?= number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Shipping Region Selection (Hidden by default for Wish Money) -->
                <div id="shipping-region-container" class="hidden w-full max-w-md space-y-2 mt-4">
                    <label class="text-lg font-medium">Shipping Region <span class="text-red-500">*</span></label>
                    <select id="checkout-shipping-region" class="w-full border px-3 py-2 outline-none focus:border-brand rounded">
                        <option value="">Select shipping region...</option>
                    </select>
                </div>

                <!-- Coupon Code Section -->
                <div class="w-full max-w-md space-y-2 mt-4">
                    <label class="text-lg font-medium">Have a Coupon Code?</label>
                    <div class="flex gap-2">
                        <input type="text" 
                               id="coupon-code" 
                               class="flex-1 border px-3 py-2 outline-none focus:border-brand rounded uppercase" 
                               placeholder="Enter coupon code"
                               maxlength="20">
                        <button type="button" 
                                id="apply-coupon-btn" 
                                class="bg-brand text-white px-6 py-2 rounded hover:bg-opacity-90 transition-colors">
                            Apply
                        </button>
                    </div>
                    <div id="coupon-message" class="text-sm hidden"></div>
                    <div id="applied-coupon" class="hidden bg-green-50 border border-green-200 p-2 rounded flex items-center justify-between">
                        <span class="text-green-700 text-sm">
                            <i class="fas fa-check-circle mr-1"></i>
                            Coupon <strong id="applied-coupon-code"></strong> applied
                        </span>
                        <button type="button" id="remove-coupon-btn" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Totals -->
                <div class="w-full max-w-md space-y-2 border-t pt-4">
                    <div class="flex justify-between">
                        <p>Subtotal</p>
                        <p id="checkout-subtotal">$<?= number_format($cartTotal, 2) ?></p>
                    </div>
                    <div id="discount-row" class="hidden flex justify-between text-green-600">
                        <p>Discount</p>
                        <p id="checkout-discount">-$0.00</p>
                    </div>
                    <div class="flex justify-between">
                        <p>Shipping</p>
                        <p id="checkout-shipping">$0.00</p>
                    </div>
                    <div class="flex justify-between font-semibold text-lg">
                        <p>Total</p>
                        <p id="checkout-total">$<?= number_format($cartTotal, 2) ?></p>
                    </div>
                </div>

                <!-- Place Order button -->
                <button type="button" id="place-order-btn" class="w-full max-w-md bg-brand text-white py-3 mt-4 rounded hover:bg-opacity-90 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Place Order
                </button>
            </div>
        </div>
    </div>

    <?php endif; ?>
</section>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-white bg-opacity-80"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-lg p-8 max-w-md w-full mx-4 text-center shadow-2xl">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Thank You!</h2>
            <p class="text-gray-600 mb-2">Your order has been placed successfully.</p>
            <p id="order-number" class="text-sm text-gray-500"></p>
        </div>
        <button id="continue-shopping-btn" class="w-full bg-brand text-white py-3 rounded hover:bg-opacity-90 transition-colors">
            Continue Shopping
        </button>
    </div>
</div>
