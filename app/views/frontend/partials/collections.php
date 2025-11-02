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
              <!-- Collection Slide - One category per slide -->
              <div class="swiper-slide">
                <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-stretch lg:h-[540px]">
                  <?php 
                  $categoryImage = !empty($category['image'])
                    ? PUBLIC_URL . $category['image']
                    : PUBLIC_URL . 'assets/images/placeholder.jpg';
                  $products = !empty($category['products']) ? array_slice($category['products'], 0, 4) : [];
                  ?>
                  <!-- Left: Category image -->
                  <div class="w-full lg:w-1/2 max-w-md mx-auto lg:mx-0 flex flex-col">
                    <a href="category.php?id=<?= $category['id'] ?>" class="relative overflow-hidden bg-gray-100 group h-[320px] sm:h-[420px] lg:h-full block">
                      <img src="<?= $categoryImage ?>" 
                           alt="<?= htmlspecialchars($category['name']) ?>"
                           class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]">
                    </a>
                    <a href="category.php?id=<?= $category['id'] ?>" class="mt-4 text-xl lg:text-3xl text-gray-900 tracking-wide text-start hover:text-brand transition-colors">
                      <?= htmlspecialchars($category['name']) ?>
                    </a>
                  </div>

                  <!-- Right: Product grid -->
                  <div class="w-full lg:w-1/2 flex">
                    <?php if (!empty($products)): ?>
                      <div class="grid grid-cols-2 grid-rows-2 gap-4 lg:gap-4 w-full">
                        <?php foreach ($products as $product): ?>
                          <a href="product.php?id=<?= $product['id'] ?>" class="group flex flex-col h-full">
                            <div class="relative overflow-hidden bg-gray-100 w-full h-72 sm:h-72 md:h-80">
                              <img src="<?= PUBLIC_URL ?><?= $product['image_path'] ?>" 
                                   alt="<?= htmlspecialchars($product['name']) ?>"
                                   class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]">
                            </div>
                            <h4 class="mt-3 text-xl lg:text-xl text-gray-900 font-medium leading-snug text-start">
                              <?= htmlspecialchars($product['name']) ?>
                            </h4>
                            <p class="mt-1 text-xl lg:text-xl font-semibold text-brand text-start">
                              $<?= number_format($product['base_price'], 0) ?>
                            </p>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php else: ?>
                      <p class="text-gray-400">No products available in this category right now.</p>
                    <?php endif; ?>
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
