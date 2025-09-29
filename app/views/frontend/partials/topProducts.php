
<section class="top-products py-12">
  <h2 class="text-4xl font-normal mb-8 text-center font-cor">Top Products</h2>
  <div class="overflow-hidden">
    <div class="topProductsSwiper relative px-4 md:px-10">
      <div class="swiper-wrapper">
        <?php foreach($topProducts as $product): ?>
        <div class="swiper-slide">
          <a href="product.php?id=<?= $product['id']?>" 
             class="flex flex-col group bg-white overflow-hidden shadow-sm">
            <div class="w-full h-64 sm:h-64 md:h-80 overflow-hidden">
              <img src="/Ego_website/public/<?= $product['image_path'] ?>" 
                   alt="<?= htmlspecialchars($product['name']) ?>" 
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            <div class="px-2 py-3 text-center">
              <p class="text-sm font-medium truncate"><?= htmlspecialchars($product['name']) ?></p>
              <p class="text-brand font-semibold">$<?= number_format($product['base_price'], 2) ?></p>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <!-- Pagination: visible only on mobile -->
      <div class="swiper-pagination mt-6 flex justify-center md:hidden"></div>
    </div>
  </div>
</section>




