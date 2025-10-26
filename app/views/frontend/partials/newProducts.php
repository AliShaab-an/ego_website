<?php 
// Initialize newProducts if not set or ensure it's an array
if (!isset($newProducts) || !is_array($newProducts)) {
    $newProducts = [];
}
?>
<section class="new-products py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-normal mb-12 text-center font-cor">New Products</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($newProducts)): ?>
                <?php foreach($newProducts as $product):?>
                <a href="product.php?id=<?= $product['id'] ?>" 
                   class="group block bg-white overflow-hidden shadow-sm hover:shadow-lg transition-shadow duration-300">
                    <div class="w-full h-48 md:h-80 overflow-hidden">
                        <img src="/Ego_website/public/<?= $product['image_path'] ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                    </div>
                    <div class="p-4 text-left">
                        <h3 class="text-gray-800 text-base md:text-lg font-medium mb-2 font-outfit"><?= htmlspecialchars($product['name']) ?></h3>
                        <!-- Price with discount display -->
                        <?php if (isset($product['discount_active']) && $product['discount_active'] && isset($product['discount_percentage']) && $product['discount_percentage'] > 0): ?>
                          <!-- Product has active discount -->
                          <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-brand font-bold text-lg">
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
                          <p class="text-brand font-bold text-lg">$<?= number_format($product['base_price'], 2) ?></p>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- No products available message -->
                <div class="col-span-full flex justify-center items-center py-20">
                    <div class="text-center text-gray-500">
                        <p class="text-lg">No new products available at the moment.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
    