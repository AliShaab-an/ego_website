<div class="flex gap-4">
  <!-- Summary Cards -->
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Categories</p>
    <p class="text-3xl font-bold text-black my-2" id="totalCategories">0</p>
    <p class="text-sm font-thin text-black">Currently in database</p>
  </div>
</div>

<!-- CATEGORIES SECTION -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Manage Categories</p>
    <button id="addCategoryBtn" class="bg-brand text-white font-semibold px-4 py-2 rounded hover:bg-opacity-90 cursor-pointer">+ Add New Category</button>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Category Name</th>
        <th>Category Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="categoryTableBody">
      <!-- Loaded via AJAX -->
    </tbody>
  </table>

  <div class="flex justify-between w-full mt-4 px-4">
    <button id="prevPage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Previous</button>
    <button id="nextPage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Next</button>
  </div>
  <p class="text-center text-red-500 mt-2" id="pageInfo"></p>
</div>

<!-- ADD CATEGORY MODAL -->
<div id="addCategoryModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Add New Category</h2>
    <form id="addCategoryForm" class="flex flex-col gap-2" enctype="multipart/form-data">
      <!-- Image Upload Box -->
      <div id="imageBox" class="flex flex-col items-center justify-center border border-black h-48 rounded mb-2 cursor-pointer relative">
          <i class="fa-solid fa-circle-plus text-xl"></i>
          <input type="file" accept="image/*" id="categoryImage" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
        </div>
      <!-- Category Name -->
      <input type="text" name="name" placeholder="Category name" class="border rounded p-2 w-full outline-none" required>

      <div class="flex justify-end gap-2">
        <button type="button" id="closeAddCategoryModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT CATEGORY MODAL -->
<div id="editCategoryModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Edit Category</h2>
    <form id="editCategoryForm" class="flex flex-col gap-2" enctype="multipart/form-data">
      <!-- Hidden ID -->
      <input type="hidden" name="id" id="editCategoryId">

      <!-- Image Preview Box -->
      <div id="editImageBox" class="flex flex-col items-center justify-center border border-black h-48 rounded mb-2 cursor-pointer relative">
        <i class="fa-solid fa-circle-plus text-xl"></i>
        <input type="file" accept="image/*" id="editCategoryImage" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
      </div>

      <!-- Category Name -->
      <input type="text" name="name" id="editCategoryName" placeholder="Category name" class="border rounded p-2 w-full outline-none" required>

      <div class="flex justify-end gap-2">
        <button type="button" id="closeEditCategoryModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Save</button>
      </div>
    </form>
  </div>
</div>


<!-- CONFIRM DELETE MODAL -->
<div id="confirmDeleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[380px]">
    <h2 class="text-xl font-bold mb-4 text-gray-900">Confirm Deletion</h2>
    <p id="confirmDeleteText" class="text-gray-700 mb-6">
      Are you sure you want to delete this category?
    </p>
    <div class="flex justify-end gap-3">
      <button id="cancelDeleteBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
      <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
    </div>
  </div>
</div>