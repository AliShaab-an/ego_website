<div class="flex justify-between p-2 mb-2">
    <p class="text-3xl font-semibold">Add New Product</p>
    <a href="#" class="bg-[rgba(183,146,103,1)] text-sm text-white px-4 py-2 rounded font-bold">Publish Product</a>
    
</div>

<div class="w-full flex gap-4">
    <div class="w-3/5  bg-white flex flex-col items-start gap-2 py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
        <p class="text-3xl font-bold mb-4">Basic Details</p>
        <form action="">

        </form>
        <label for="" class="font-bold">Product Name</label>
        <input type="text" class="w-full p-2 border border-gray-500 rounded" required>
        <label for="" class="font-bold">Product description</label>
        <input type="text" class="w-full h-16 px-2  border border-gray-500 rounded">
        <p class="font-bold text-xl">Pricing</p>
        <label for="" class="font-bold">Product price</label>
        <input type="number" class="w-full p-2 border border-gray-500 rounded" placeholder="$00.00" min="0.00" required>
        <div class="flex flex-row  gap-2">
            <div class="">
                <label for="" class="font-bold">Discounted Price(Optional)</label>
                <input type="number" class="w-full p-2 border border-gray-500 rounded">
            </div>
            <div>
                <label for="" class="font-bold">Weight</label>
                <input type="number" class="w-full p-2 border border-gray-500 rounded">
            </div>
        </div>

        <p class="font-bold text-xl">Inventory</p>
        <div id="variantContainer">
            <!-- First variant row -->
            <div  class="flex flex-row gap-2">
                <div>
                    <label for="" class="font-bold">Stock Quantity</label>
                    <input type="number" class="w-40 text-center h-10 p-2 border border-gray-500 rounded">
                </div>
                <div>
                    <label for="" class="font-bold">Colors</label>
                    <select id="colorsDropdown" class="w-40 h-10 text-center text-sm p-2 border border-gray-500 rounded">
                        <option value="">Color</option>
                    </select>
                </div>
                <div>
                    <label for="" class="font-bold">Size</label>
                    <select class="w-40 h-10 text-center text-sm p-2 border border-gray-500 rounded">
                        <option value="">Size</option>
                    </select>
                </div>
                <div class="text-center">
                    <label for="" class="font-bold">Add</label>
                    <button id="addInventory" class="h-10 text-sm px-4 border border-gray-500 rounded"><i class="fi fi-rr-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="w-2/5 bg-white flex flex-col items-start py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
        <p class="text-2xl font-bold mb-4">Upload Product Image</p>
        <p class="font-bold mb-2">Product Image</p>
        <div class="flex flex-col w-full h-48 items-center justify-center gap-2 border border-black rounded mb-2">
            <i class="fa-solid fa-circle-plus"></i>
            <p class="text-lg">Add Image</p>
        </div>
        <div class="grid grid-cols-3 w-full p-2">
            
            <div class="relative flex flex-col items-center justify-center gap-2 w-20 h-24 border border-black rounded">
                <i class="fa-solid fa-circle-xmark absolute top-2 right-2 hover:text-red-500"></i>
            </div>

            <div class="relative flex flex-col items-center justify-center gap-2 w-20 h-24 border border-black rounded">
                <i class="fa-solid fa-circle-xmark absolute top-2 right-2 hover:text-red-500"></i>
            </div>

            <div class="flex flex-col items-center justify-center gap-2 w-32 h-24 border-2 border-dashed border-black rounded">
                <i class="fa-solid fa-circle-plus"></i>
                <p>Add Image</p>
            </div>
        </div>
        <p class="text-lg font-bold my-2">Categories</p>
        <p class="font-semibold">Product Categories</p>

        <select name="" id="categoryDropdown" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-gray-700 shadow-sm
            focus:border-[rgba(183,146,103,1)] focus:ring-2 focus:ring-[rgba(183,146,103,0.3)] focus:outline-none
            transition duration-200 ease-in-out cursor-pointer">
            <option class="bg-white text-gray-700 font-medium hover:bg-gray-100" value="">Select Your Category</option>
        </select>
            
        
        
        <p class="font-semibold mt-2">Add Color</p>
        <form id="colorForm" class="flex items-center gap-2 ">
            <div class="flex flex-col">
                <input type="text" id="colorName" name="name" placeholder="Color Name" 
                class="p-2 border border-gray-400 rounded w-40">
            </div>
            <div class="flex flex-col">   
                <input type="color" id="colorPicker" name="hex_code" value="#f01105ff"
                class="w-28 h-10 p-1 border border-gray-400 rounded shadow cursor-pointer">
            </div>
            <button type="submit" class="h-10 text-sm px-4 border border-gray-500 rounded"><i class="fi fi-rr-plus"></i></button>
        </form>
        <p class="font-semibold mt-2">Add Size</p>
        <form id="SizeForm" class="flex items-center gap-2 mt-2 ">
            <div class="flex flex-col">
                <input type="text" id="colorName" name="name" placeholder="Size name" 
                class="p-2 border border-gray-400 rounded w-40">
            </div>
            <div class="flex flex-col">   
                <input type="text" id="sizeType" name="type" placeholder="Type"
                class="w-28 h-10 p-1 border border-gray-400 rounded shadow cursor-pointer">
            </div>
            <button type="submit" class="h-10 text-sm px-4 border border-gray-500 rounded"><i class="fi fi-rr-plus"></i></button>
        </form>
    </div>
</div>

