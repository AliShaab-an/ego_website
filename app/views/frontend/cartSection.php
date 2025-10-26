<?php

require_once __DIR__ . '/../../controllers/CartController.php';

try {
    $cartController = new CartController();
    $cartData = $cartController->getCartItems();
    
    $cartItems = $cartData['items'] ?? [];
    $cartTotal = $cartData['total'] ?? 0;
    $cartCount = $cartData['count'] ?? 0;
    
} catch (Exception $e) {
    error_log("Cart error: " . $e->getMessage());
    $cartItems = [];
    $cartTotal = 0;
    $cartCount = 0;
}

?>

<section class="max-w-7xl mx-auto px-4 py-10">
  <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center font-cor">Cart</h1>

  <?php if (empty($cartItems)): ?>
    <!-- Empty Cart -->
    <div class="text-center py-16">
      <i class="fi fi-rr-shopping-cart text-6xl text-gray-300 mb-4"></i>
      <h2 class="text-2xl font-semibold text-gray-600 mb-2">Your cart is empty</h2>
      <p class="text-gray-500 mb-6">Add some products to get started!</p>
      <a href="shop.php" class="bg-brand text-white px-8 py-3 rounded-lg hover:bg-brand-dark transition-colors">
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
            <th class="py-2 text-xl">Subtotal</th>
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

      <!-- Shipping Region Selection -->
      <div class="space-y-3">
        <label for="shipping-region" class="block text-sm font-medium">Select Shipping Region:</label>
        <select id="shipping-region" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
          <option value="">Choose a region...</option>
        </select>
      </div>

      <!-- Shipping Fee Display -->
      <div id="shipping-display" class="gap-2 px-2 py-2 border items-center hidden">
        <i class="fi fi-rr-dot-circle text-xl"></i>
        <div class="flex-1 flex justify-between">
          <p id="shipping-region-name">Shipping Fee</p>
          <p id="shipping-fee">$0.00</p>
        </div>
      </div>

      <!-- Totals -->
      <div class="space-y-2">
        <div class="flex justify-between border-b py-2">
          <p>Subtotal</p>
          <p id="cart-subtotal">$<?= number_format($cartTotal, 2) ?></p>
        </div>
        <div class="flex justify-between border-b py-2" id="shipping-total-row" style="display: none;">
          <p>Shipping</p>
          <p id="shipping-total">$0.00</p>
        </div>
        <div class="flex justify-between font-semibold">
          <p>Total</p>
          <p id="cart-total">$<?= number_format($cartTotal, 2) ?></p>
        </div>
      </div>

      <!-- Checkout -->
      <button class="w-full bg-brand text-white py-3 rounded">
        <a href="checkout.php">Checkout (<?= $cartCount ?> items)</a>
      </button>
    </div>
  </div>

  <?php endif; ?>
</section>

<script>
// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Remove item functionality
    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const productId = cartItem.dataset.productId;
            const size = cartItem.dataset.size;
            const color = cartItem.dataset.color;
            
            removeFromCart(productId, size, color);
        });
    });

    // Quantity change functionality
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const productId = cartItem.dataset.productId;
            const size = cartItem.dataset.size;
            const color = cartItem.dataset.color;
            const quantityDisplay = cartItem.querySelector('.quantity-display');
            const currentQuantity = parseInt(quantityDisplay.textContent);
            
            let newQuantity = currentQuantity;
            if (this.classList.contains('plus-btn')) {
                newQuantity = currentQuantity + 1;
            } else if (this.classList.contains('minus-btn') && currentQuantity > 1) {
                newQuantity = currentQuantity - 1;
            }
            
            if (newQuantity !== currentQuantity) {
                updateCartItem(productId, size, color, newQuantity);
            }
        });
    });
});

function removeFromCart(productId, size, color) {
    const formData = new FormData();
    formData.append('productId', productId);
    formData.append('size', size);
    formData.append('color', color);

    fetch('/api/remove-from-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to update cart display
            location.reload();
        } else {
            alert('Failed to remove item: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while removing the item');
    });
}

function updateCartItem(productId, size, color, quantity) {
    const formData = new FormData();
    formData.append('productId', productId);
    formData.append('size', size);
    formData.append('color', color);
    formData.append('quantity', quantity);

    fetch('/api/update-cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to update cart display
            location.reload();
        } else {
            alert('Failed to update item: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the item');
    });
}
</script>