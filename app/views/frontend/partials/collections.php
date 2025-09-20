<section class="py-12 bg-white">
  <div class="container mx-auto px-4">
    <!-- Section Title -->
    <div class="text-center mb-10">
      <p class="text-sm uppercase tracking-widest font-outfit text-brand">Check Out Our</p>
      <h2 class="text-5xl font-cor">Collections</h2>
    </div>

    <!-- Collections Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 border border-black">
     
      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $category): ?>
          <a href="category.php?id=<?= $category['id'] ?>" 
             class="group block overflow-hidden rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
            
            <div class="relative w-full h-72">
              <img src="/Ego_website/public/admin/uploads/<?= htmlspecialchars($category['image'] ?? 'placeholder.jpg') ?>" 
                   alt="<?= htmlspecialchars($category['name']) ?>" 
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            
            <div class="p-4 bg-white">
              <h3 class="text-lg font-semibold text-gray-800 group-hover:text-[rgba(183,146,103,1)]">
                <?= htmlspecialchars($category['name']) ?>
              </h3>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-full text-center text-gray-500">No collections available</p>
      <?php endif; ?>
    </div>
  </div>
</section>