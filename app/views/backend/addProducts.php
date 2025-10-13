<div>
    <form id="productForm" enctype="multipart/form-data">
        <div class="flex justify-between p-2 mb-2">
            <p class="text-3xl font-semibold">Add New Product</p>
            <button type="submit" class="bg-brand text-sm text-white px-4 py-2 rounded font-bold cursor-pointer">Publish Product</button>
        </div>

        <div class="w-full flex gap-4">
            <div class="w-3/5  bg-white flex flex-col items-start gap-2 py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
                <p class="text-3xl font-bold mb-2">Basic Details</p>
                <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product Name</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded outline-none" placeholder="Enter Product Name" required>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product description</label>
                    <textarea type="text" name="description" class="w-full h-16 border border-gray-300 px-3 py-2 outline-none rounded" placeholder="Enter Product Description"></textarea>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="is_top" name="is_top" value="1" 
                    class="w-5 h-5 border-gray-400 rounded">
                    <label for="is_top" class="font-bold">Mark as Top Product</label>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product price</label>
                    <input type="number" name="base_price" class="w-full p-2 border border-gray-300 rounded outline-none" placeholder="00.00" min="0.00" required>
                </div>
                <div class="flex flex-row  gap-2 mt-2">
                    <div class="">
                        <label class="font-bold mb-2">Category</label>
                        <select name="category_id" id="categoryDropdown" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-gray-700 shadow-sm
                        transition duration-200 ease-in-out cursor-pointer outlin-none">
                        <option class="bg-white text-gray-700 font-medium hover:bg-gray-100" value="">Select Your Category</option>
                        </select>
                    </div>
                    <div>
                        <label for="" class="font-bold mb-2">Weight (Optional)</label>
                        <input type="number" name="weight" class="w-full p-2 border border-gray-300 rounded outline-none" placeholder="00.00">
                    </div>
                </div>
            </div>
            <div class="w-2/5 bg-white flex flex-col items-start py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
              <p class="text-3xl font-bold mb-2">Basic Details</p>
              <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product Discount</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded outline-none" placeholder="Enter Discount Percentage">
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                    class="w-5 h-5 border-gray-400 rounded">
                    <label for="is_active" class="font-bold">Mark Discount as active</label>
                </div>
            </div>
              
        </div>

        <p class="font-bold text-xl my-2">Inventory</p>
        <div class="w-full bg-white flex flex-col items-start py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)] mt-4">

            <div id="colorContainer" class="w-full"></div>
            <button type="button" id="addColorBtn" class="mt-4 px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">
            <i class="fi fi-rr-plus mr-1"></i> Add Variant
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
      <p class="font-bold mb-2">Variant Images</p>
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






