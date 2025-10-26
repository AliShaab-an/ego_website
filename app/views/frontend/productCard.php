<?php
    require_once __DIR__ . '/../../config/path.php';
    include CONT  . '/frontend/ProductController.php';
    $productController = new ProductController();
    $id = $_GET['id'] ?? null;
    if($id){
      $product = $productController->getProductById($id);
    }else{
      header("Location: shop.php");
      exit;
    }
?>


<section class="max-w-7xl mx-auto px-4 py-10 product-detail-section">
  <!-- Loading skeleton - shown initially, hidden when content loads -->
  <div id="productLoadingSkeleton" class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- Left: Image skeleton -->
    <div class="flex flex-col md:flex-row gap-4">
      <div class="hidden md:flex flex-col gap-4 w-24">
        <div class="w-24 h-24 bg-gray-200 animate-pulse rounded"></div>
        <div class="w-24 h-24 bg-gray-200 animate-pulse rounded"></div>
        <div class="w-24 h-24 bg-gray-200 animate-pulse rounded"></div>
      </div>
      <div class="flex-1 w-full h-[400px] md:h-[540px]">
        <div class="w-full h-full bg-gray-200 animate-pulse rounded"></div>
      </div>
    </div>
    
    <!-- Right: Content skeleton -->
    <div class="space-y-6">
      <div class="h-8 bg-gray-200 animate-pulse rounded w-3/4"></div>
      <div class="h-6 bg-gray-200 animate-pulse rounded w-1/2"></div>
      <div class="space-y-2">
        <div class="h-4 bg-gray-200 animate-pulse rounded"></div>
        <div class="h-4 bg-gray-200 animate-pulse rounded w-5/6"></div>
        <div class="h-4 bg-gray-200 animate-pulse rounded w-4/6"></div>
      </div>
      <div class="space-y-4">
        <div class="h-10 bg-gray-200 animate-pulse rounded"></div>
        <div class="h-10 bg-gray-200 animate-pulse rounded"></div>
        <div class="h-12 bg-gray-200 animate-pulse rounded"></div>
      </div>
    </div>
  </div>

  <!-- Actual content - initially hidden -->
  <div id="productContent" class="grid grid-cols-1 md:grid-cols-2 gap-10" style="display: none;">
    
    <!-- Left: Image Gallery -->
    <div class="flex flex-col md:flex-row gap-4">
      
      <!-- Thumbnails (desktop: vertical, mobile: horizontal under main image) -->
      <div class="hidden md:flex flex-col gap-4 w-24 order-1">
        <?php foreach($product['images'] as $img): ?>
          <img 
            src="/Ego_website/public/<?= $img ?>" 
            loading="lazy"
            class="thumbnail-image cursor-pointer hover:ring-2 hover:ring-brand transition-all rounded">
        <?php endforeach; ?>
      </div>

      <!-- Main Image -->
      <div class="flex-1 w-full h-[400px] md:h-[540px] order-2">
        <img 
            id="mainImage" 
            loading="lazy"
            src="/Ego_website/public/<?= $product['images'][0] ?>" 
            alt="<?= htmlspecialchars($product['name']) ?>"
            class="w-full h-full object-cover rounded"/>
      </div>
    </div>

    <!-- Thumbnails on mobile (horizontal row under main image) -->
    <div class="flex w-24 md:hidden gap-4 mt-4 justify-center">
      <?php foreach($product['images'] as $img): ?>
          <img 
            loading="lazy"
            src="/Ego_website/public/<?= $img ?>" 
            class="thumbnail-image cursor-pointer hover:ring-2 hover:ring-brand transition-all rounded"/>
        <?php endforeach; ?>
    </div>

    <!-- Right: Product Details -->
    <div class="flex flex-col gap-6">
      <h1 class="text-3xl font-light font-outfit"><?= htmlspecialchars($product['name']) ?></h1>
      
      <!-- Price Section with Discount Display -->
      <div class="price-section">
        <?php if (isset($product['discount_active']) && $product['discount_active'] && isset($product['discount_percentage']) && $product['discount_percentage'] > 0): ?>
          <!-- Product has active discount -->
          <div class="flex items-center gap-3">
            <span class="text-2xl font-semibold text-brand" id="product-price">
              $<?= number_format($product['base_price'] * (1 - $product['discount_percentage'] / 100), 2) ?>
            </span>
            <span class="text-lg text-gray-500 line-through" id="original-price">
              $<?= number_format($product['base_price'], 2) ?>
            </span>
            <span class="bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">
              -<?= number_format($product['discount_percentage'], 0) ?>%
            </span>
          </div>
        <?php else: ?>
          <!-- No discount -->
          <p class="text-2xl font-semibold text-brand" id="product-price">$<?= number_format($product['base_price'], 2) ?></p>
        <?php endif; ?>
      </div>
      
      <!-- Hidden data for JavaScript -->
      <input type="hidden" id="base-price" value="<?= $product['base_price'] ?>">
      <input type="hidden" id="discount-percentage" value="<?= $product['discount_percentage'] ?? 0 ?>">
      <input type="hidden" id="discount-active" value="<?= ($product['discount_active'] ?? 0) ? '1' : '0' ?>">
      <script type="application/json" id="variant-data">
        <?= json_encode($product['variants']) ?>
      </script>

      <!-- Sizes -->
      <div class="flex flex-col gap-2">
        <h3 class="text-xl">Size:</h3>
        <div id="sizeContainer" class="flex gap-2">
          <?php 
          $uniqueSizes = [];
          foreach($product['variants'] as $variant): 
            if(!empty($variant['size']) && !in_array($variant['size'], $uniqueSizes)): 
              $uniqueSizes[] = $variant['size'];
          ?>
              <button
                class="size-option px-6 py-2 border-2 border-gray-300 text-xl rounded-full cursor-pointer"
                data-size="<?= htmlspecialchars($variant['size']) ?>"
                >
                  <p><?= htmlspecialchars($variant['size']) ?></p>
              </button>
            <?php endif; ?>
          <?php endforeach;?>
        </div>
      </div>

      <!-- Colors -->
      <div class="flex flex-col gap-2">
        <h3 class="text-xl">Colors:</h3>
        <div id="colorContainer" class="flex gap-2">
          <?php 
          $uniqueColors = [];
          foreach ($product['variants'] as $variant): 
            if (!empty($variant['color']) && !in_array($variant['color'], $uniqueColors)): 
              $uniqueColors[] = $variant['color'];
          ?>
              <div 
                class="color-option flex items-center gap-2 px-3 py-2 border-2 border-gray-300 rounded-full cursor-pointer"
                data-color="<?= htmlspecialchars($variant['color']) ?>"
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
  </div> <!-- End of productContent -->
</section>

        // Basic validation
        if (!selectedSize) {
            alert('Please select a size');
            return;
        }
        if (!selectedColor) {
            alert('Please select a color');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('productId', productId);
        formData.append('size', selectedSize);
        formData.append('color', selectedColor);
        formData.append('quantity', quantity);

        // Show loading state
        const $button = $(this);
        const originalText = $button.text();
        $button.text('Adding...').prop('disabled', true);

        // Send AJAX request
        $.ajax({
            url: '/Ego_website/public/api/add-to-cart.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update cart count
                    updateCartCount();
                    
                    // Show success message
                    showCartMessage(response.message, 'success');
                    
                    // Reset button
                    $button.text('Added to Cart!').removeClass('bg-brand').addClass('bg-green-500');
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        $button.text(originalText).removeClass('bg-green-500').addClass('bg-brand').prop('disabled', false);
                    }, 2000);
                } else {
                    showCartMessage(response.message || 'Failed to add item to cart', 'error');
                    $button.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                showCartMessage('Server error. Please try again.', 'error');
                $button.text(originalText).prop('disabled', false);
            }
        });
    });

    // Update cart count on page load
    updateCartCount();
});
</script>