

<div id="popup" class="fixed inset-0 backdrop-blur-md bg-white/20 flex items-center justify-center hidden z-[999px]">
    <div class="bg-white rounded-lg p-6 w-md relative">
        <button id="closeBtn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <h2 class="text-xl font-bold mb-4">Upload Category Image</h2>
        <p class="mb-4">image</p>
        <form id="addCategoryForm" class="w-full" enctype="multipart/form-data">
            <div id="imageBox" class="flex flex-col items-center justify-center border border-black h-48 rounded mb-4 cursor-pointer relative">
                    <i class="fa-solid fa-circle-plus text-xl"></i>
                    <input type="file" accept="image/*" id="categoryImage" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
            </div>
            <label for="categoryName" class="text-lg font-bold">Category Name</label>
            <input id="categoryName" name="name" type="text" class="w-full p-2 border border-gray-500 rounded my-4">
            <button id="okBtn" class="px-4 py-2 bg-white border border-black text-black rounded hover:bg-[rgba(183,146,103,1)] hover:text-white hover:border-none transition">Submit</button> 
        </form>             
    </div>
</div>
