<section class="shop-products py-12 ">
    <div class="flex justify-between items-center p-8">
      <div id="openFilter" class="flex items-center justify-center cursor-pointer text-gray-600">
        <p class="text-lg ">Filters</p>
        <i class="fi fi-rr-angle-small-right text-lg mt-2"></i>
      </div>
      
      <p class="text-gray-600">Showing <?= count($products['products']); ?> out of <?= $products['totalProducts']; ?>  results</p>
    </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 p-8 text-start">
    <?php foreach($products['products'] as $product): ?>
      <a href="product.php?id=<?= $product['id'] ?>" 
          class="flex flex-col group">
        <div class="md:w-full md:h-96 overflow-hidden">
          <img src="/Ego_website/public/<?= $product['image_path'] ?>" 
                alt="<?= htmlspecialchars($product['name']) ?>" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </div>
        <p class="mt-2 text-gray-600 text-lg"><?= htmlspecialchars($product['name']) ?></p>
        <p class="text-brand font-bold">$<?= number_format($product['base_price'], 2) ?></p>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <div class="flex justify-end items-center p-8 gap-4">
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
  </div>

    
</section>