<section class="p-10">
    <h2 class="text-4xl font-normal mb-8 text-center font-cor">New Products</h2>
    <div class="max-w-7xl mx-auto p-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($newProducts as $product):?>
            <a href="product.php?id=<?= $product['id'] ?>" class="flex flex-col group">
                <div class=" w-36 h-36 md:w-full md:h-96 overflow-hidden">
                    <img src="/Ego_website/public/<?= $product['image_path'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                </div>
                <div class="p-2 text-start">
                    <p class="text-gray-600 text-sm font-outfit"><?= htmlspecialchars($product['name']) ?></p>
                    <p class="text-brand font-bold">$<?= number_format($product['base_price'], 2) ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
    