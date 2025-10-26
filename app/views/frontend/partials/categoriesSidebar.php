
<?php
// Load dynamic filter data
require_once __DIR__ . '/../../../config/path.php';
require_once MODELS . 'Category.php';
require_once MODELS . 'Colors.php';
require_once MODELS . 'Sizes.php';

try {
    $categories = Category::getPaginated(100, 0); // Get all categories
    $colors = Colors::getAllColors();
    $sizes = Sizes::getPaginated(100, 0); // Get all sizes
} catch (Exception $e) {
    $categories = [];
    $colors = [];
    $sizes = [];
}
?>
<aside id="filterSidebar"
    class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 border border-brand">

    <div class="flex justify-between items-center p-4 border-b border-brand">
        <h2 class="text-lg font-bold">Filters</h2>
        <button id="closeFilter" class="text-gray-600 text-2xl cursor-pointer">&times;</button>
    </div>

    <div class="p-4 overflow-y-auto h-full">
        
        <!-- Categories Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Categories</h3>
            <div class="flex flex-col space-y-2 text-gray-500" id="categoriesFilter">
                <?php if (!empty($categories)): ?>
                    <?php foreach($categories as $category): ?>
                        <label class="flex items-center">
                            <input type="checkbox" name="categories[]" value="<?= $category['id'] ?>" class="mr-2 category-filter"> 
                            <?= htmlspecialchars($category['name']) ?>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-400">No categories available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colors Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Colors</h3>
            <div class="flex flex-wrap gap-2" id="colorsFilter">
                <?php if (!empty($colors)): ?>
                    <?php foreach($colors as $color): ?>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="colors[]" value="<?= $color['id'] ?>" class="sr-only color-filter">
                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 relative overflow-hidden"
                                 style="background-color: <?= htmlspecialchars($color['hex_code']) ?>"
                                 title="<?= htmlspecialchars($color['name']) ?>">
                                <div class="hidden absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                                    <i class="fi fi-rr-check text-white text-sm"></i>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-400">No colors available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sizes Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Sizes</h3>
            <div class="flex flex-wrap gap-2" id="sizesFilter">
                <?php if (!empty($sizes)): ?>
                    <?php foreach($sizes as $size): ?>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="sizes[]" value="<?= $size['id'] ?>" class="sr-only size-filter">
                            <div class="px-3 py-2 border border-gray-300 rounded text-sm hover:border-brand transition-colors">
                                <?= htmlspecialchars($size['name']) ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-400">No sizes available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Price Range Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Price Range</h3>
            <div class="space-y-4">
                <div class="flex gap-2">
                    <input type="number" id="minPrice" name="minPrice" placeholder="Min" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:border-brand focus:outline-none" 
                           min="0" value="0">
                    <input type="number" id="maxPrice" name="maxPrice" placeholder="Max" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:border-brand focus:outline-none" 
                           min="0" value="10000">
                </div>
                <div class="text-sm text-gray-500">
                    <span id="priceRangeDisplay">$0 - $10,000</span>
                </div>
            </div>
        </div>

        <!-- Apply Filters Button -->
        <div class="sticky bottom-0 bg-white pt-4 border-t border-gray-200">
            <div class="flex gap-2">
                <button id="clearFilters" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors">
                    Clear
                </button>
                <button id="applyFilters" 
                        class="flex-1 px-4 py-2 bg-brand text-white rounded hover:bg-brand-dark transition-colors">
                    Apply
                </button>
            </div>
        </div>
    </div>
</aside>





