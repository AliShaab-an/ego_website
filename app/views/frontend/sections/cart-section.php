<?php
// Cart data passed from FrontendController
$cartItems = $cartItems ?? [];
$cartTotal = $cartTotal ?? 0;
$cartCount = $cartCount ?? 0;
?>

<section class="max-w-7xl mx-auto px-4 py-10">
  <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center font-cor">Cart</h1>

  <?php if (empty($cartItems)): ?>
    <!-- Empty Cart -->
    <div class="text-center py-16">
      <i class="fi fi-rr-shopping-cart text-6xl text-gray-300 mb-4"></i>
      <h2 class="text-2xl font-semibold text-gray-600 mb-2">Your cart is empty</h2>
      <p class="text-gray-500 mb-6">Add some products to get started!</p>
      <a href="<?= page_url('shop') ?>" class="bg-brand text-white px-8 py-3 rounded-lg hover:bg-brand-dark transition-colors">
        Continue Shopping
      </a>
    </div>
  <?php else: ?>
    
  <div class="flex flex-col lg:flex-row gap-8">
    <!-- Cart Items -->
    <div class="flex-1">
      <!-- Desktop table -->
      <table class="hidden md:table w-full border-collapse">
        <thead>
          <tr class="border-b">
            <th class="py-2 text-xl text-left">Products</th>
            <th class="py-2 text-xl">Quantity</th>
            <th class="py-2 text-xl">Color</th>
            <th class="py-2 text-xl">Size</th>
            <th class="py-2 text-xl">Price</th>
          </tr>
        </thead>
        <tbody id="cart-items-desktop">
          <?php foreach ($cartItems as $item): ?>
          <tr class="border-b text-center cart-item" data-product-id="<?= htmlspecialchars($item['product_id']) ?>" 
              data-size="<?= htmlspecialchars($item['size'] ?? '') ?>" 
              data-color="<?= htmlspecialchars($item['color'] ?? '') ?>"
              data-price="<?= htmlspecialchars($item['price'] ?? 0) ?>">
            <td class="py-4 text-left">
              <div class="flex gap-3 items-center">
                <button class="cursor-pointer hover:text-red-500 remove-item-btn">
                  <i class="fi fi-rr-cross-small"></i>
                </button>
                <img src="<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'assets/images/placeholder.png' ?>" 
                     alt="<?= htmlspecialchars($item['name'] ?? 'Product') ?>" 
                     class="w-20 h-24 object-cover">
                <p><?= htmlspecialchars($item['name'] ?? 'Product') ?></p>
              </div>
            </td>
            <td class="py-4">
              <div class="flex items-center justify-center gap-2">
                <button class="quantity-btn minus-btn px-2 py-1 border rounded hover:bg-gray-100">-</button>
                <span class="quantity-display mx-3"><?= (int)$item['quantity'] ?></span>
                <button class="quantity-btn plus-btn px-2 py-1 border rounded hover:bg-gray-100">+</button>
              </div>
            </td>
            <td class="py-4"><?= htmlspecialchars($item['color'] ?? 'N/A') ?></td>
            <td class="py-4"><?= htmlspecialchars($item['size'] ?? 'N/A') ?></td>
            <td class="py-4 item-subtotal">$<?= number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Mobile cards -->
      <div class="space-y-4 md:hidden">
        <p class="text-xl font-outfit">Products</p>
        <div id="cart-items-mobile">
          <?php foreach ($cartItems as $item): ?>
          <div class="border-t border-b p-4 shadow-sm cart-item" 
               data-product-id="<?= htmlspecialchars($item['product_id']) ?>" 
               data-size="<?= htmlspecialchars($item['size'] ?? '') ?>" 
               data-color="<?= htmlspecialchars($item['color'] ?? '') ?>"
               data-price="<?= htmlspecialchars($item['price'] ?? 0) ?>">
            <div class="flex items-center gap-3 mb-3">
              <img src="<?= !empty($item['image']) ? htmlspecialchars($item['image']) : 'assets/images/placeholder.png' ?>" 
                   alt="<?= htmlspecialchars($item['name'] ?? 'Product') ?>" 
                   class="w-20 h-24 object-cover rounded">
              <div class="flex-1">
                <h3 class="font-semibold"><?= htmlspecialchars($item['name'] ?? 'Product') ?></h3>
                <p class="text-sm text-gray-500">
                  <?= htmlspecialchars($item['size'] ?? 'N/A') ?> / <?= htmlspecialchars($item['color'] ?? 'N/A') ?>
                </p>
              </div>
              <button class="text-red-500 remove-item-btn">
                <i class="fi fi-rr-cross-small"></i>
              </button>
            </div>
            <div class="flex items-center justify-between text-sm">
              <div class="flex items-center gap-4 border px-4 py-2 text-lg text-brand rounded">
                <button class="quantity-btn minus-btn">-</button>
                <span class="quantity-display"><?= (int)$item['quantity'] ?></span>
                <button class="quantity-btn plus-btn">+</button>
              </div>
              <p class="text-xl font-semibold item-subtotal">$<?= number_format($item['total'] ?? ($item['price'] * $item['quantity']), 2) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Cart Summary -->
    <div class="w-full lg:w-80 flex flex-col gap-4 p-6 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)] rounded-lg">
      <p class="text-xl font-semibold">Cart Summary</p>

      <!-- Totals -->
      <div class="space-y-2">
        <div class="flex justify-between border-b py-2">
          <p>Subtotal</p>
          <p id="cart-subtotal">$<?= number_format($cartTotal, 2) ?></p>
        </div>
        <div class="flex justify-between font-semibold text-lg">
          <p>Total</p>
          <p id="cart-total">$<?= number_format($cartTotal, 2) ?></p>
        </div>
      </div>

      <p class="text-sm text-gray-500 text-center">Shipping calculated at checkout</p>

      <!-- Checkout -->
      <button id="checkout-btn" class="w-full bg-brand text-white py-3 rounded hover:bg-opacity-90 transition-colors">
        Checkout (<?= $cartCount ?> items)
      </button>
    </div>
  </div>

  <?php endif; ?>
</section>