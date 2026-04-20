<?php 
// Initialize categoriesWithProducts if not set or ensure it's an array
if (!isset($categoriesWithProducts) || !is_array($categoriesWithProducts)) {
    $categoriesWithProducts = [];
}
?>

<section class="collections py-20 bg-white">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Section Header -->
    <div class="text-center mb-12">
  <p class="text-sm tracking-[0.2em] text-brand uppercase mb-3">Check Out Our</p>
  <h2 class="text-4xl font-light text-gray-900 font-cor">Collections</h2>
    </div>
    
    <div class="collections-container relative">
      <!-- Collections Slider -->
      <div class="collectionsSwiper overflow-hidden">
        <div class="swiper-wrapper">
          <?php if (!empty($categoriesWithProducts)): ?>
            <?php foreach($categoriesWithProducts as $category): ?>
              <?php 
              if (empty($category['products'])) continue;

              $categoryImage = !empty($category['image'])
                ? PUBLIC_URL . $category['image']
                : PUBLIC_URL . 'assets/images/placeholder.jpg';
              $products = array_slice($category['products'], 0, 4);
              $productCount = count($products);

              // Columns for the product grid
              $cols = match($productCount) {
                1 => 'grid-cols-1',
                2 => 'grid-cols-2',
                3 => 'grid-cols-3',
                default => 'grid-cols-2',
              };
              // 4 products: 2 rows; 1-3 products: 1 row
              $rows = $productCount === 4 ? 'grid-rows-2' : 'grid-rows-1';
              ?>
              <div class="swiper-slide">
                <!-- Fixed height on desktop so both sides are always equal -->
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 lg:h-[520px]">

                  <!-- Left: Category image — fills full desktop height -->
                  <div class="lg:w-5/12 flex-shrink-0 flex flex-col">
                    <a href="<?= page_url('category', ['id' => $category['id']]) ?>"
                       class="block overflow-hidden bg-gray-100 group h-[300px] lg:h-auto lg:flex-1">
                      <img src="<?= $categoryImage ?>"
                           alt="<?= htmlspecialchars($category['name']) ?>"
                           class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                    </a>
                    <a href="<?= page_url('category', ['id' => $category['id']]) ?>"
                       class="mt-3 text-xl lg:text-2xl text-gray-900 tracking-wide hover:text-brand transition-colors font-light flex-shrink-0">
                      <?= htmlspecialchars($category['name']) ?>
                    </a>
                  </div>

                  <!-- Right: Product grid — same height as left on desktop -->
                  <div class="lg:w-7/12 grid <?= $cols ?> <?= $rows ?> gap-3
                              <?= $productCount === 4 ? 'h-[440px] lg:h-full' : '' ?>">
                    <?php foreach ($products as $product): ?>
                      <a href="<?= page_url('product', ['id' => $product['id']]) ?>"
                         class="group flex flex-col min-h-0 overflow-hidden">
                        <!-- Image fills all available cell space -->
                        <div class="flex-1 min-h-0 overflow-hidden bg-gray-100
                                    <?= $productCount !== 4 ? 'aspect-[3/4] lg:aspect-auto' : '' ?>">
                          <img src="<?= PUBLIC_URL ?><?= $product['image_path'] ?>"
                               alt="<?= htmlspecialchars($product['name']) ?>"
                               class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                        </div>
                        <!-- Text strip — always visible, never clipped -->
                        <div class="flex-shrink-0 pt-2 pb-1">
                          <h4 class="text-sm font-medium text-gray-900 truncate leading-snug">
                            <?= htmlspecialchars($product['name']) ?>
                          </h4>
                          <p class="text-sm font-semibold text-brand">
                            $<?= number_format($product['base_price'], 0) ?>
                          </p>
                        </div>
                      </a>
                    <?php endforeach; ?>
                  </div>

                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- No categories available -->
            <div class="swiper-slide">
              <div class="h-96 flex items-center justify-center bg-gray-100 rounded-lg">
                <div class="text-center text-gray-500">
                  <p class="text-lg">No collections available at the moment.</p>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Navigation Arrows -->
      <div class="hidden lg:block collections-nav absolute top-1/2 transform -translate-y-1/2 -left-4 z-10">
        <button class="collections-prev bg-white hover:bg-gray-50 shadow-lg rounded-full p-3 transition-all duration-300 hover:scale-110">
          <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
      </div>
      <div class="hidden lg:block collections-nav absolute top-1/2 transform -translate-y-1/2 -right-4 z-10">
        <button class="collections-next bg-white hover:bg-gray-50 shadow-lg rounded-full p-3 transition-all duration-300 hover:scale-110">
          <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
      
      <!-- Pagination Dots (Hidden on mobile, visible on desktop) -->
      <div class="collections-pagination hidden lg:flex justify-center mt-8 space-x-2"></div>
    </div>
  </div>
</section>
