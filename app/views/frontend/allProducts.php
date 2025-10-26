<?php 
// Initialize products if not set
if (!isset($products) || !is_array($products)) {
    $products = ['products' => [], 'totalProducts' => 0];
}
?>
<section class="shop-products py-12 ">
    <div class="flex justify-between items-center p-8">
      <div id="openFilter" class="flex items-center justify-center cursor-pointer text-gray-600">
        <p class="text-lg ">Filters</p>
        <i class="fi fi-rr-angle-small-right text-lg mt-2"></i>
      </div>
      
      <p class="text-gray-600">Showing <span id="productsCount"><?= count($products['products']); ?></span> out of <span id="totalProducts"><?= $products['totalProducts']; ?></span> results</p>
    </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 p-8 text-start" id="productsContainer">
    <?php if (!empty($products['products'])): ?>
      <?php foreach($products['products'] as $product): ?>
        <a href="product.php?id=<?= $product['id'] ?>" 
            class="flex flex-col group">
          <div class="md:w-full md:h-96 overflow-hidden">
            <img src="/Ego_website/public/<?= $product['image_path'] ?>" 
                  alt="<?= htmlspecialchars($product['name']) ?>" 
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
          </div>
          <p class="mt-2 text-gray-600 text-lg"><?= htmlspecialchars($product['name']) ?></p>
          <!-- Price with discount display -->
          <?php if (isset($product['discount_active']) && $product['discount_active'] && isset($product['discount_percentage']) && $product['discount_percentage'] > 0): ?>
            <!-- Product has active discount -->
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-brand font-bold">
                $<?= number_format($product['discounted_price'], 2) ?>
              </span>
              <span class="text-gray-500 text-sm line-through">
                $<?= number_format($product['base_price'], 2) ?>
              </span>
              <span class="bg-red-500 text-white px-1.5 py-0.5 rounded text-xs font-semibold">
                -<?= number_format($product['discount_percentage'], 0) ?>%
              </span>
            </div>
          <?php else: ?>
            <!-- No discount -->
            <p class="text-brand font-bold">$<?= number_format($product['base_price'], 2) ?></p>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <!-- Initial loading state - will be replaced by JavaScript -->
      <div class="col-span-full flex justify-center items-center py-20">
        <div class="flex flex-col items-center gap-4">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
          <p class="text-gray-600 text-lg">Loading products...</p>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <div class="flex justify-end items-center p-8 gap-4" id="paginationContainer">
    <?php 
    // Initialize pagination variables if not set
    $page = isset($page) ? $page : 1;
    $currentPage = isset($currentPage) ? $currentPage : 1;
    ?>
    <?php if ($page > 1): ?>
      <div class="border-2 border-brand px-2 py-1 text-brand">
        <i class="fi fi-rr-angle-small-left text-lg"></i>
      </div>
      <?php for($i = 1; $i <= $page; $i++): ?>
        <a href="?page=<?= $i ?>" 
            class=" <?= $i == $currentPage ? 'bg-brand text-white' : 'text-brand font-bold' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
      <div class="border-2 border-brand px-2 py-1 text-brand">
        <i class="fi fi-rr-angle-small-right text-lg"></i>
      </div>
    <?php endif; ?>
  </div>

    
</section>