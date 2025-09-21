<div>
    <form id="productForm" enctype="multipart/form-data">
        <div class="flex justify-between p-2 mb-2">
            <p class="text-3xl font-semibold">Add New Product</p>
            <button type="submit" class="bg-brand text-sm text-white px-4 py-2 rounded font-bold">Publish Product</button>
        </div>

        <div class="w-full flex gap-4">
            <div class="w-3/5  bg-white flex flex-col items-start gap-2 py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
                <p class="text-3xl font-bold mb-2">Basic Details</p>
                <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product Name</label>
                    <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded outline-none" required>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product description</label>
                    <textarea type="text" name="description" class="w-full h-16 border border-gray-300 px-3 py-2 outline-none rounded"></textarea>
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" id="is_top" name="is_top" value="1" 
                    class="w-5 h-5 border-gray-400 rounded">
                    <label for="is_top" class="font-bold">Mark as Top Product</label>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <label for="" class="font-bold">Product price</label>
                    <input type="number" name="base_price" class="w-full p-2 border border-gray-300 rounded outline-none" placeholder="$00.00" min="0.00" required>
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
                        <input type="number" name="weight" class="w-full p-2 border border-gray-300 rounded outline-none">
                    </div>
                </div>
            </div>
            <div class="w-2/5 bg-white flex flex-col items-start py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
                <p class="text-2xl font-bold mb-4">Upload Product Image</p>
                <!-- Main Image -->
                <div class="mb-4 w-full">
                    <p class="font-bold mb-2">Main Image</p>
                    <label class="flex flex-col w-full h-48 items-center justify-center gap-2 border border-black rounded cursor-pointer">
                    <i class="fa-solid fa-circle-plus text-brand"></i>
                    <p class="text-lg text-brand">Click to upload main image</p>
                    <input type="file" name="images[]" id="mainImage" accept="image/*" class="hidden">
                    </label>
                    <div id="mainImagePreview" class="mt-2"></div>
                </div>

                <!-- Extra Images -->
                <div class="w-full">
                    <p class="font-bold mb-2">Other Images</p>
                    <div id="extraImagesContainer" class="grid grid-cols-3 gap-2">
                    <!-- Extra image slots will appear here -->
                    </div>
                    <button type="button" id="addExtraImage" class="mt-3 px-4 py-2 text-sm border border-gray-400 rounded hover:bg-gray-100">
                    <i class="fa-solid fa-circle-plus mr-1"></i> Add More Images
                    </button>
                </div>
            </div>
        </div>
        <p class="font-bold text-xl my-2">Inventory</p>
        <div class="w-full bg-white flex flex-col items-start py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)] mt-4">
            
            <div id="variantContainer">
                <!-- First variant row -->
                <div class="flex flex-row gap-2 items-end">
                    <div class="flex flex-col">
                        <label for="" class="font-bold mb-2">Quantity</label>
                        <input name="variants[0][quantity]" type="number" placeholder="0" class="w-40 text-center h-10 p-2 border border-gray-300 outline-none rounded" min="0">
                    </div>
                    <div class="flex flex-col">
                        <label for="" class="font-bold mb-2">Colors</label>
                        <select id="colorsDropdown" name="variants[0][color_id]" class="w-40 h-10 text-center text-sm p-2 border border-gray-300 outline-none rounded">
                            <option value="">Color</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="" class="font-bold mb-2">Size</label>
                        <select id="sizesDropdown" name="variants[0][size_id]" class="w-40 h-10 text-center text-sm p-2 border border-gray-300 outline-none rounded">
                            <option value="">Size</option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label class="font-bold mb-2">Price (Optional)</label>
                        <input type="number" name="variants[0][price]" 
                        class="w-32 text-center h-10 p-2 border border-gray-300 rounded outline-none" min="0" step="0.01">
                    </div>
                    <div class="flex flex-col">
                        <button type="button" id="addInventory" class="h-10 text-sm px-4 border border-gray-500 rounded"><i class="fi fi-rr-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <p class="font-bold text-xl my-2 font-outfit">Colors & Sizes</p>
    <div class="w-full bg-white flex flex-col items-start py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)] mt-4">
        <p class="font-semibold mt-2">Add Color</p>
        <form id="colorForm" class="flex gap-2 mt-2 w-full">
            <div class="flex flex-col">
                <input type="text" id="colorName" name="name" placeholder="Color Name" 
                class="p-2 border border-gray-300 rounded w-40 outline-none">
            </div>
            <div class="flex flex-col">   
                <input type="color" id="colorPicker" name="hex_code" value="#000000ff"
                class="w-28 h-10 p-1 border border-gray-300 rounded cursor-pointer">
            </div>
            <button type="submit" class="h-10 text-sm px-4 border border-gray-500 rounded"><i class="fi fi-rr-plus"></i></button>
        </form>
        <p class="font-semibold">Add Size</p>
        <form id="sizeForm" class="flex items-center gap-2 mt-2 ">
            <div class="flex flex-col gap-2">
                <input type="text" id="sizeName" name="name" placeholder="Size Name" 
                class="p-2 border border-gray-300 rounded w-40 outline-none" required>
            </div>
            <div class="flex flex-col">   
                <input type="text" id="sizeType" name="type" placeholder="Type"
                class="w-28 h-10 p-2 border border-gray-300 rounded shadow cursor-pointer outline-none" required>
            </div>
            <button type="submit" class="h-10 text-sm px-4 border border-gray-500 rounded"><i class="fi fi-rr-plus"></i></button>
        </form>
    </div>
</div>



