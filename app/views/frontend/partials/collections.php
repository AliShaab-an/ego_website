<?php 
// Initialize categoriesWithProducts if not set or ensure it's an array
if (!isset($categoriesWithProducts) || !is_array($categoriesWithProducts)) {
    $categoriesWithProducts = [];
}
?>

<section class="collections py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4">
    <h2 class="text-4xl font-normal mb-12 text-center font-cor">Collections</h2>
    
    <div class="collections-container relative">
      <!-- Collections Slider -->
      <div class="collectionsSwiper overflow-hidden">
        <div class="swiper-wrapper">
          <?php if (!empty($categoriesWithProducts)): ?>
            <?php foreach($categoriesWithProducts as $index => $category): ?>
              <!-- Collection Slide -->
              <div class="swiper-slide">
                <div class="collection-grid h-full">
                  <?php if (!empty($category['products'])): ?>
                    <?php if ($index % 2 == 0): ?>
                      <!-- Layout 1: Large image left, 3 small right -->
                      <div class="grid grid-cols-3 gap-4 h-[500px]">
                        <!-- Large product (first) -->
                        <div class="col-span-2 row-span-2">
                          <a href="product.php?id=<?= $category['products'][0]['id'] ?>" 
                             class="block h-full group relative overflow-hidden rounded-lg">
                            <img src="/Ego_website/public/<?= $category['products'][0]['image_path'] ?>" 
                                 alt="<?= htmlspecialchars($category['products'][0]['name']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-all duration-300"></div>
                            <div class="absolute bottom-4 left-4 text-white">
                              <h3 class="text-2xl font-bold"><?= htmlspecialchars($category['name']) ?></h3>
                              <p class="text-lg"><?= htmlspecialchars($category['products'][0]['name']) ?></p>
                              <p class="text-xl font-semibold">$<?= number_format($category['products'][0]['base_price'], 2) ?></p>
                            </div>
                          </a>
                        </div>
                        
                        <!-- Small products (remaining) -->
                        <?php for($i = 1; $i < min(4, count($category['products'])); $i++): ?>
                          <div class="col-span-1">
                            <a href="product.php?id=<?= $category['products'][$i]['id'] ?>" 
                               class="block h-full group relative overflow-hidden rounded-lg">
                              <img src="/Ego_website/public/<?= $category['products'][$i]['image_path'] ?>" 
                                   alt="<?= htmlspecialchars($category['products'][$i]['name']) ?>"
                                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                              <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-all duration-300"></div>
                              <div class="absolute bottom-2 left-2 text-white">
                                <p class="text-sm font-medium"><?= htmlspecialchars($category['products'][$i]['name']) ?></p>
                                <p class="text-sm font-semibold">$<?= number_format($category['products'][$i]['base_price'], 2) ?></p>
                              </div>
                            </a>
                          </div>
                        <?php endfor; ?>
                      </div>
                    <?php else: ?>
                      <!-- Layout 2: 3 small left, Large image right -->
                      <div class="grid grid-cols-3 gap-4 h-[500px]">
                        <!-- Small products (first 3) -->
                        <?php for($i = 1; $i < min(4, count($category['products'])); $i++): ?>
                          <div class="col-span-1">
                            <a href="product.php?id=<?= $category['products'][$i]['id'] ?>" 
                               class="block h-full group relative overflow-hidden rounded-lg">
                              <img src="/Ego_website/public/<?= $category['products'][$i]['image_path'] ?>" 
                                   alt="<?= htmlspecialchars($category['products'][$i]['name']) ?>"
                                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                              <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-all duration-300"></div>
                              <div class="absolute bottom-2 left-2 text-white">
                                <p class="text-sm font-medium"><?= htmlspecialchars($category['products'][$i]['name']) ?></p>
                                <p class="text-sm font-semibold">$<?= number_format($category['products'][$i]['base_price'], 2) ?></p>
                              </div>
                            </a>
                          </div>
                        <?php endfor; ?>
                        
                        <!-- Large product (first) -->
                        <div class="col-span-2 row-span-2">
                          <a href="product.php?id=<?= $category['products'][0]['id'] ?>" 
                             class="block h-full group relative overflow-hidden rounded-lg">
                            <img src="/Ego_website/public/<?= $category['products'][0]['image_path'] ?>" 
                                 alt="<?= htmlspecialchars($category['products'][0]['name']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-all duration-300"></div>
                            <div class="absolute bottom-4 right-4 text-white text-right">
                              <h3 class="text-2xl font-bold"><?= htmlspecialchars($category['name']) ?></h3>
                              <p class="text-lg"><?= htmlspecialchars($category['products'][0]['name']) ?></p>
                              <p class="text-xl font-semibold">$<?= number_format($category['products'][0]['base_price'], 2) ?></p>
                            </div>
                          </a>
                        </div>
                      </div>
                    <?php endif; ?>
                  <?php else: ?>
                    <!-- No products in this category -->
                    <div class="h-[500px] flex items-center justify-center bg-gray-200 rounded-lg">
                      <div class="text-center text-gray-500">
                        <h3 class="text-2xl font-bold mb-2"><?= htmlspecialchars($category['name']) ?></h3>
                        <p>No products available</p>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- No categories available -->
            <div class="swiper-slide">
              <div class="h-[500px] flex items-center justify-center bg-gray-200 rounded-lg">
                <div class="text-center text-gray-500">
                  <p class="text-lg">No collections available at the moment.</p>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Navigation Arrows -->
      <div class="collections-nav absolute top-1/2 transform -translate-y-1/2 left-4 z-10">
        <button class="collections-prev bg-white/90 hover:bg-white shadow-lg rounded-full p-3 transition-all duration-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
      </div>
      <div class="collections-nav absolute top-1/2 transform -translate-y-1/2 right-4 z-10">
        <button class="collections-next bg-white/90 hover:bg-white shadow-lg rounded-full p-3 transition-all duration-300">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
      
      <!-- Pagination Dots -->
      <div class="collections-pagination flex justify-center mt-8 space-x-2"></div>
    </div>
  </div>
</section>