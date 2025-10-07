
<section class="max-w-7xl mx-auto px-4 py-10">
  <h1 class="text-4xl md:text-5xl font-bold mb-8 text-center font-cor">Cart</h1>

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
        <tbody>
          <tr class="border-b text-center">
            <td class="py-4 text-left">
              <div class="flex gap-3 items-center">
                <button class="cursor-pointer hover:text-red-500"><i class="fi fi-rr-cross-small"></i></button>
                <img src="assets/images/placeholder.png" alt="" class="w-20 h-24 object-cover">
                <p>Linen oversized coat</p>
              </div>
            </td>
            <td class="py-4">1</td>
            <td class="py-4">White</td>
            <td class="py-4">S</td>
            <td class="py-4">$320</td>
          </tr>
        </tbody>
      </table>

      <!-- Mobile cards -->
      <div class="space-y-4 md:hidden">
        <p class="text-xl font-outfit">Products</p>
        <div class="border-t border-b  p-4 shadow-sm">
          <div class="flex items-center gap-3 mb-3">
            <img src="assets/images/placeholder.png" alt="" class="w-20 h-24 object-cover rounded">
            <div class="flex-1">
              <h3 class="font-semibold">Linen oversized coat</h3>
              <p class="text-sm text-gray-500">S / White</p>
            </div>
            <button class="text-red-500"><i class="fi fi-rr-cross-small"></i></button>
          </div>
          <div class="flex items-center justify-between text-sm">
            <div class="flex items-center gap-4 border px-4 py-2 text-lg text-brand rounded">
              <button>-</button>
              <span>1</span>
              <button>+</button>
            </div>
            <p class="text-xl font-semibold">$320</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Cart Summary -->
    <div class="w-full lg:w-80 flex flex-col gap-4 p-6 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)] rounded-lg">
      <p class="text-xl font-semibold">Cart Summary</p>

      <!-- Currency toggle -->
      <div class="flex gap-2 flex-wrap">
        <span class="text-sm px-3 py-1 rounded-full border hover:text-brand hover:border-brand cursor-pointer">LEB</span>
        <span class="text-sm px-3 py-1 rounded-full border hover:text-brand hover:border-brand cursor-pointer">MEA</span>
        <span class="text-sm px-3 py-1 rounded-full border hover:text-brand hover:border-brand cursor-pointer">EUR</span>
        <span class="text-sm px-3 py-1 rounded-full border hover:text-brand hover:border-brand cursor-pointer">USA</span>
      </div>

      <!-- Shipping -->
      <div class="flex gap-2 px-2 py-2 border items-center">
        <i class="fi fi-rr-dot-circle text-xl"></i>
        <div class="flex-1 flex justify-between">
          <p>Shipping Fee/Kg (MEA)</p>
          <p>$25</p>
        </div>
      </div>

      <!-- Totals -->
      <div class="space-y-2">
        <div class="flex justify-between border-b py-2">
          <p>Subtotal</p>
          <p>$420</p>
        </div>
        <div class="flex justify-between font-semibold">
          <p>Total</p>
          <p>$445</p>
        </div>
      </div>

      <!-- Checkout -->
      <button class="w-full bg-brand text-white py-3 rounded"><a href="checkout.php">Checkout</a></button>
    </div>
  </div>
</section>