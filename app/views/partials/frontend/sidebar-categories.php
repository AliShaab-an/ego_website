<!-- Filter Sidebar - Data loaded dynamically via JavaScript -->
<aside id="filterSidebar"
    class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 border border-brand">

    <div class="flex justify-between items-center p-4 border-b border-brand">
        <h2 class="text-lg font-bold">Filters</h2>
        <button id="closeFilter" class="text-gray-600 text-2xl cursor-pointer">&times;</button>
    </div>

    <div class="p-4 overflow-y-auto" style="height: calc(100% - 140px);">
        
        <!-- Categories Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Categories</h3>
            <div class="flex flex-col space-y-2 text-gray-500" id="categoryFilters">
                <!-- Populated by JavaScript -->
                <p class="text-gray-400 text-sm">Loading...</p>
            </div>
        </div>

        <!-- Colors Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Colors</h3>
            <div class="flex flex-wrap gap-2" id="colorFilters">
                <!-- Populated by JavaScript -->
                <p class="text-gray-400 text-sm">Loading...</p>
            </div>
        </div>

        <!-- Sizes Filter -->
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Sizes</h3>
            <div class="flex flex-wrap gap-2" id="sizeFilters">
                <!-- Populated by JavaScript -->
                <p class="text-gray-400 text-sm">Loading...</p>
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
    </div>

    <!-- Apply Filters Button - Fixed to bottom of sidebar -->
    <div class="absolute bottom-0 left-0 right-0 w-full bg-white p-4 border-t border-gray-200 shadow-lg">
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
</aside>





