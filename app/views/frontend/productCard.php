<section class="max-w-7xl mx-auto px-4 py-10">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    
    <!-- Left: Image Gallery -->
    <div class="flex flex-col md:flex-row gap-4">
      
      <!-- Thumbnails (desktop: vertical, mobile: horizontal under main image) -->
      <div class="hidden md:flex flex-col gap-4 w-24 order-1">
        <?php foreach($product['images'] as $img): ?>
          <img 
            onclick="changeImage(this)"
            src="/Ego_website/public/<?= $img ?>" 
            loading="lazy"
            class="cursor-pointer"/>
        <?php endforeach; ?>
      </div>

      <!-- Main Image -->
      <div class="flex-1 w-full h-[400px] md:h-[540px] order-2">
        <img 
            id="mainImage" 
            loading="lazy"
            src="/Ego_website/public/<?= $product['images'][0] ?>" 
            alt="<?= htmlspecialchars($product['name']) ?>"
            class="w-full h-full object-cover"/>
      </div>
    </div>

    <!-- Thumbnails on mobile (horizontal row under main image) -->
    <div class="flex w-24 md:hidden gap-4 mt-4 justify-center">
      <?php foreach($product['images'] as $img): ?>
          <img 
            loading="lazy"
            src="/Ego_website/public/<?= $img ?>" 
            class="cursor-pointer"/>
        <?php endforeach; ?>
    </div>

    <!-- Right: Product Details -->
    <div class="flex flex-col gap-6">
      <h1 class="text-3xl font-light font-outfit"><?= htmlspecialchars($product['name']) ?></h1>
      <p class="text-2xl font-semibold text-brand">$<?= number_format($product['base_price'], 2) ?></p>

      <!-- Sizes -->
      <div class="flex flex-col gap-2">
        <h3 class="text-xl">Size:</h3>
        <div id="sizeContainer" class="flex gap-2">
          <?php foreach($product['variants'] as $variant): ?>
            <?php if(!empty($variant['size'])): ?>
              <button
                class="px-6 py-2 border-2 border-gray-300 text-xl rounded-full cursor-pointer"
                >
                  <?= htmlspecialchars($variant['size']) ?>
              </button>
            <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>

      <!-- Colors -->
      <div class="flex flex-col gap-2">
        <h3 class="text-xl">Colors:</h3>
        <div id="colorContainer" class="flex gap-2">
          <?php foreach ($product['variants'] as $variant): ?>
            <?php if (!empty($variant['color'])): ?>
              <div 
                class="color-option flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-full cursor-pointer"
                >
                <div class="w-8 h-8 border-2 border-gray-300 rounded-full cursor-pointer" 
                  style="background-color: <?= $variant['color_hex'] ?: 'gray' ?>;"
                  title="<?= htmlspecialchars($variant['color']) ?>">
                </div>
                <p class="text-xl"><?= htmlspecialchars($variant['color']) ?></p>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Quantity + Add to Cart -->
      <div class="flex gap-4 items-center">
        <div class="flex items-center gap-4 border px-4 py-2 text-lg text-brand rounded">
          <button class="cursor-pointer" type="button" id="qty-minus">-</button>
          <span id="qty-value">1</span>
          <button class="cursor-pointer" type="button" id="qty-plus">+</button>
        </div>
        <button 
        class="bg-brand text-white px-8 py-2 rounded cursor-pointer"
        id="add-to-cart"
        data-product-id="<?= $product['id'] ?>">
        Add to Cart
        </button>
      </div>

      <input type="hidden" id="selected-size" value="">
      <input type="hidden" id="selected-color" value="">

      <!-- Accordion -->
      <div class="border-t border-b w-full border-brand divide-y text-brand">
        <button class="accordion-btn w-full flex justify-between items-center p-3 text-lg font-medium text-left cursor-pointer">
          Description
          <i class="fi fi-rr-arrow-small-right text-xl"></i>
        </button>
        <div class="accordion-content hidden p-4">
          <?= $product['description'] ?>
        </div>
        <button class="w-full flex justify-between items-center p-3 text-lg font-medium text-left cursor-pointer">
          Shipping & Returns
          <i class="fi fi-rr-arrow-small-right text-xl"></i>
        </button>
        <button class="w-full flex justify-between items-center p-3 text-lg font-medium text-left cursor-pointer">
          Product Care
          <i class="fi fi-rr-arrow-small-right text-xl"></i>
        </button>
      </div>
    </div>
  </div>
</section>