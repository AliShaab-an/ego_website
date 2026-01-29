<div class="w-full">
    <form id="productForm" enctype="multipart/form-data">
        <div class="flex justify-between items-center mb-6">
            <h1 id="pageTitle" class="text-3xl font-bold text-gray-800">Add New Product</h1>
            <button id="publishBtn" type="submit" class="bg-[rgba(183,146,103,1)] hover:bg-[rgba(160,120,80,1)] text-white font-semibold px-6 py-2 rounded-lg transition cursor-pointer">Publish Product</button>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2 bg-white rounded-lg shadow-md p-6 space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Basic Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Product Name</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]" placeholder="Enter Product Name" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Product Description</label>
                            <textarea name="description" class="w-full h-28 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]" placeholder="Enter Product Description"></textarea>
                        </div>
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="is_top" name="is_top" value="1" class="w-5 h-5 border-gray-300 rounded">
                                <span class="text-gray-700 font-semibold">Mark as Top Product</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Product Price</label>
                            <input type="number" name="base_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]" placeholder="0.00" min="0.00" step="0.01" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Category</label>
                                <select name="category_id" id="categoryDropdown" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]">
                                    <option value="">Select Your Category</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Weight (Optional)</label>
                                <input type="number" name="weight" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]" placeholder="0.00" step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                    class="w-5 h-5 border-gray-400 rounded">
                    <label for="is_active" class="font-bold">Mark Discount as active</label>
                </div>
            </div>

            <div class="col-span-1 bg-white rounded-lg shadow-md p-6 space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Discount Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Product Discount</label>
                            <input type="number" id="discount" name="discount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.2)]" placeholder="Enter Discount Percentage" min="0" max="100" step="0.01">
                        </div>
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="w-5 h-5 border-gray-300 rounded">
                                <span class="text-gray-700 font-semibold">Mark Discount as Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Inventory</h2>
            <div id="colorContainer" class="space-y-4"></div>
            <button type="button" id="addColorBtn" class="mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg cursor-pointer transition font-semibold">
                <i class="fa-solid fa-plus mr-2"></i>Add Variant
            </button>
        </div>
    </form>
</div>


<template id="colorTemplate">
  <div class="color-block border border-gray-300 rounded p-4 mt-4 space-y-3">
    <div class="flex justify-between items-center mb-2">
      <p class="font-bold text-lg">Add Variant</p>
      <button type="button" class="removeColorBtn text-red-500 cursor-pointer">Remove</button>
    </div>

    <!-- Color Selection -->
    <div>
      <select name="variants[0][color_id]" class="colorDropdown w-44 h-10 text-center text-sm p-2 border border-gray-300 outline-none rounded">
        <option value="">Select Color</option>
      </select>
    </div>

    <!-- Variant Images -->
    <div class="variant-images-section">
      <p class="font-bold mb-2">Variant Images Note: the First Image is the main Image</p>
      <div class="extraImagesContainer flex gap-2"></div>
      <button type="button" class="addExtraImage mt-2 px-3 py-1 border border-gray-400 rounded hover:bg-gray-100 text-sm">
        <i class="fa-solid fa-circle-plus mr-1"></i> Add Image
      </button>
    </div>

    <!-- Sizes -->
    <div class="sizesContainer"></div>
    <button type="button" class="addSizeBtn mt-2 px-3 py-1 border rounded hover:bg-gray-100 cursor-pointer">
      <i class="fa-solid fa-plus mr-1"></i> Add Info
    </button>
  </div>
</template>

<!-- Template for size row -->
<template id="sizeTemplate">
  <div class="size-row flex gap-2 items-end mt-2">
    <div class="flex flex-col">
        <label for="" class="font-bold mb-2">Size</label>
        <select name="variants[0][size_id]" class="sizesDropdown w-40 h-10 text-center text-sm p-2 border border-gray-300 outline-none rounded">
            <option value="">Size</option>
        </select>
    </div>
    <div class="flex flex-col">
        <label for="" class="font-bold mb-2">Quantity</label>
        <input name="variants[0][quantity]" type="number" placeholder="0" class="w-40 text-center h-10 p-2 border border-gray-300 outline-none rounded" min="0">
    </div>
    <div class="flex flex-col">
        <label class="font-bold mb-2">Price (Optional)</label>
        <input type="number" name="variants[0][price]" 
        class="w-32 text-center h-10 p-2 border border-gray-300 rounded outline-none" min="0" step="0.01">
    </div>
    <button type="button" class="removeSizeBtn h-10 px-2 py-1 border rounded text-red-500 cursor-pointer">Remove</button>
  </div>
</template>






