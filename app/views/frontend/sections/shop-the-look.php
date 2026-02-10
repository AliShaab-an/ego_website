
<?php 
// Initialize shopTheLookProducts if not set or ensure it's an array
if (!isset($shopTheLookProducts) || !is_array($shopTheLookProducts)) {
    $shopTheLookProducts = [];
}
?>
<section class="shop-the-look py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-4xl font-normal mb-12 text-center font-cor">Shop the Look</h2>
    <div class="overflow-hidden">
      <div class="shopTheLookSwiper relative">
        <div class="swiper-wrapper">
          <?php if (!empty($shopTheLookProducts)): ?>
            <?php foreach($shopTheLookProducts as $product): ?>
            <div class="swiper-slide">
              <a href="<?= page_url('product', ['id' => $product['id']]) ?>" 
                 class="block group bg-white overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
                <div class="w-full h-64 sm:h-72 md:h-80 overflow-hidden">
                  <img src="<?= PUBLIC_URL ?><?= $product['image_path'] ?>" 
                       alt="<?= htmlspecialchars($product['name']) ?>" 
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-4 text-center">
                  <h3 class="text-lg font-medium mb-2 truncate text-gray-800"><?= htmlspecialchars($product['name']) ?></h3>
                  <!-- Price with discount display -->
                  <?php if (isset($product['discount_active']) && $product['discount_active'] && isset($product['discount_percentage']) && $product['discount_percentage'] > 0): ?>
                    <!-- Product has active discount -->
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                      <span class="text-brand font-bold text-xl">
                        $<?= number_format($product['discounted_price'], 2) ?>
                      </span>
                      <span class="text-gray-500 text-sm line-through">
                        $<?= number_format($product['base_price'], 2) ?>
                      </span>
                      <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold">
                        -<?= number_format($product['discount_percentage'], 0) ?>%
                      </span>
                    </div>
                  <?php else: ?>
                    <!-- No discount -->
                    <p class="text-brand font-bold text-xl">$<?= number_format($product['base_price'], 2) ?></p>
                  <?php endif; ?>
                </div>
              </a>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
          <!-- No products available message -->
          <div class="swiper-slide">
            <div class="flex flex-col items-center justify-center p-8 text-gray-500 h-96">
              <p class="text-lg">There are no products</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
        
        <!-- Navigation Arrows -->
        <div class="shopTheLook-nav absolute top-1/2 transform -translate-y-1/2 left-4 z-10">
          <button class="shopTheLook-prev bg-white/90 hover:bg-white shadow-lg rounded-full p-3 transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
        </div>
        <div class="shopTheLook-nav absolute top-1/2 transform -translate-y-1/2 right-4 z-10">
          <button class="shopTheLook-next bg-white/90 hover:bg-white shadow-lg rounded-full p-3 transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </button>
        </div>
        
        <!-- Pagination: hidden -->
        <div class="swiper-pagination mt-6 hidden"></div>
      </div>
    </div>
  </div>
</section>
