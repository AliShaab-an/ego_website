
<aside id="filterSidebar"
    class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 border border-brand">

    <div class="flex justify-between items-center p-4 border-b border-brand">
        <h2 class="text-lg font-bold">Filters</h2>
        <button id="closeFilter" class="text-gray-600 text-2xl cursor-pointer">&times;</button>
    </div>

    <div class="p-4 overflow-y-auto h-full">
        
        <div class="mb-6 text-start">
            <h3 class="text-xl font-semibold mb-4">Categories</h3>
            <div class="flex flex-col space-y-2 text-gray-500">
                <label><input type="checkbox" name="category[]" value="dresses" class="mr-2"> Dresses</label>
                <label><input type="checkbox" name="category[]" value="tshirts" class="mr-2"> T-shirts</label>
                <label><input type="checkbox" name="category[]" value="tops" class="mr-2"> Tops</label>
                <label><input type="checkbox" name="category[]" value="shorts" class="mr-2"> Shorts</label>
                <label><input type="checkbox" name="category[]" value="bags" class="mr-2"> Bags</label>
                <label><input type="checkbox" name="category[]" value="shoes" class="mr-2"> Shoes</label>
            </div>
        </div>

        <div class="w-full">
            <h3 class="text-xl font-semibold mb-3 text-start">Price</h3>

            <div class="relative w-full h-2 rounded">
                <div class="flex justify-between mt-3 text-sm font-medium text-gray-700">
                    <span id="minPriceValue">$5</span>
                    <span id="maxPriceValue">$2000</span>
                </div>
                <div class="flex justify-center items-center">
                    <div class="w-5 h-5 border rounded-full"></div>
                    <div class="flex-1 h-1 bg-black mx-2 "></div>
                    <div class="w-5 h-5 border rounded-full"></div>
                </div>
            </div>
        </div>
    </div>
</aside>





