<section class="category-products py-8">
    <div class="flex justify-between items-center p-8">
        <div class="flex items-center gap-4">
            <a href="shop.php" class="text-gray-600 hover:text-black transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Shop
            </a>
        </div>

        <p class="text-gray-600">
            Showing <span id="showingCount">0</span> out of <span id="totalCount">0</span> results
        </p>
    </div>

    <!-- Product Grid -->
    <div id="productsContainer" class="grid grid-cols-2 lg:grid-cols-4 gap-6 p-8 text-start">
        
    </div>

    <!-- Pagination -->
    <div class="flex justify-center items-center p-8 gap-4">
        <button id="prevPage" class="border-2 border-brand px-2 py-1 text-brand hover:bg-brand hover:text-white">
            <i class="fi fi-rr-angle-small-left text-lg"></i>
        </button>

        <div id="paginationNumbers" class="flex gap-2 text-brand font-bold"></div>

        <button id="nextPage" class="border-2 border-brand px-2 py-1 text-brand hover:bg-brand hover:text-white">
            <i class="fi fi-rr-angle-small-right text-lg"></i>
        </button>
    </div>
</section>