<div class="flex justify-between p-2 mb-2">
    <p class="text-3xl font-semibold">Add New Product</p>
    <a href="#" class="bg-[rgba(183,146,103,1)] text-sm text-white px-4 py-2 rounded font-bold">Publish Product</a>
    
</div>

<div class="w-full h-dvh flex gap-4">
    <div class="w-3/5  bg-white flex flex-col items-start gap-2 py-4 px-4 shadow-[0_0_14.36px_-3.16px_rgba(0,0,0,0.25)]">
        <p class="text-3xl font-bold mb-4">Basic Details</p>
        <label for="" class="font-bold">Product Name</label>
        <input type="text" class="w-full p-2 border border-gray-500 rounded">
        <label for="" class="font-bold">Product description</label>
        <input type="text" class="w-full h-16 px-2  border border-gray-500 rounded">
        <p class="font-bold text-xl">Pricing</p>
        <label for="" class="font-bold">Product price</label>
        <input type="text" class="w-full p-2 border border-gray-500 rounded">
        <div class="flex flex-row  gap-2">
            <div class="">
                <label for="" class="font-bold">Discounted Price(Optional)</label>
                <input type="text" class="w-full p-2 border border-gray-500 rounded">
            </div>
            <div>
                <label for="" class="font-bold">Weight</label>
                <input type="text" class="w-full p-2 border border-gray-500 rounded">
            </div>
        </div>
        <p class="font-bold text-xl">Inventory</p>
        <div class="flex flex-row  gap-2">
            <div class="">
                <label for="" class="font-bold">Stock Quantity</label>
                <input type="text" class="w-full p-2 border border-gray-500 rounded">
            </div>
            <div>
                <label for="" class="font-bold">Stock Status</label>
                <input type="text" class="w-full p-2 border border-gray-500 rounded">
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
        <select name="" id="" class="w-full border border-gray-500 rounded my-2 p-2">
            <option value="">Select Your Category</option>
        </select>
        <p class="font-bold">Select colors</p>
        <div>

        </div>
    </div>

</div>

