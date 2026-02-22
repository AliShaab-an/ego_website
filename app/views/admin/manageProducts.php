<div class="flex gap-4">
  <div class="w-[256px] h-[130px] bg-white p-4 shadow">
    <p class="text-l font-bold text-black">Total Products</p>
    <p class="text-3xl font-bold my-2" id="totalProducts">0</p>
    <p class="text-sm font-thin text-black">In database</p>
  </div>
</div>

<div class="flex justify-between items-center mb-4 gap-3 flex-wrap mt-2">
  <input type="text" id="searchProduct" class="border p-2 rounded w-1/3" placeholder="Search product...">

  <select id="filterCategory" class="border p-2 rounded">
    <option value="">All Categories</option>
  </select>

  <select id="filterStatus" class="border p-2 rounded">
    <option value="">All Status</option>
    <option value="1">Active</option>
    <option value="0">Inactive</option>
  </select>

  <select id="filterTop" class="border p-2 rounded">
    <option value="">All</option>
    <option value="1">Top Products</option>
    <option value="0">Not Top</option>
  </select>

  <button id="searchBtn" class="bg-brand text-white px-4 py-2 rounded">Filter</button>
  <button id="addProductPageBtn" class="bg-brand text-white px-4 py-2 rounded">+ Add Product</button>
</div>

  <table class="table-fixed w-full">
    <colgroup>
      <col style="width:4%">
      <col style="width:24%">
      <col style="width:12%">
      <col style="width:8%">
      <col style="width:10%">
      <col style="width:9%">
      <col style="width:33%">
    </colgroup>
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4 text-center">#</th>
        <th class="text-left pl-3">Name</th>
        <th class="text-center">Category</th>
        <th class="text-center">Price</th>
        <th class="text-center">Stock</th>
        <th class="text-center">Status</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody id="productTableBody"></tbody>
  </table>

  <div class="flex justify-between w-full mt-4 px-4">
    <button id="prevPage" class="px-3 py-1 bg-white border rounded shadow cursor-pointer">Previous</button>
    <button id="nextPage" class="px-3 py-1 bg-white border rounded shadow cursor-pointer">Next</button>
  </div>
</div>

<div id="editQuickModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Quick Edit Product</h2>
    <form id="quickEditForm" class="flex flex-col gap-4">
      <input type="hidden" name="id" id="quickEditId">
      <div>
        <label for="quickEditName" class="block text-sm font-medium">Product Name</label>
        <input type="text" name="name" id="quickEditName"
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
      </div>
      <div>
        <label for="quickEditPrice" class="block text-sm font-medium">Base Price ($)</label>
        <input type="number" name="base_price" id="quickEditPrice" step="0.01" min="0"
          class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
      </div>
      <div class="flex items-center gap-2">
        <input type="checkbox" id="quickEditTop" name="is_top" value="1" />
        <label for="quickEditTop" class="text-sm font-medium">Mark as Top Product</label>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeQuickModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- CONFIRM DELETE -->
<div id="confirmDeleteModal"
    class="hidden fixed inset-0 bg-white/20 backdrop-blur-md z-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96">
    <h2 class="text-lg font-semibold mb-4 text-red-600">Confirm Action</h2>
    <p id="confirmDeleteText" class="text-gray-700 mb-6 text-center">
      Are you sure you want to delete this product?
    </p>

    <div class="flex justify-end gap-2">
      <button id="cancelDeleteBtn"
              class="bg-white px-2 py-1 rounded border cursor-pointer">
        Cancel
      </button>
      <button id="confirmDeleteBtn"
              class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 cursor-pointer">
        Delete Permanently
      </button>
    </div>
  </div>
</div>

<!-- VARIANTS MODAL -->
<div id="variantsModal" class="hidden fixed inset-0 bg-white/20 backdrop-blur-md z-50">
  <div class="flex items-center justify-center h-full">
    <div class="bg-white p-6 rounded-lg shadow-xl w-[700px] max-h-[85vh] overflow-y-auto">
      <div class="flex justify-between items-center mb-5">
        <div>
          <h2 class="text-xl font-bold text-gray-900">Product Variants</h2>
          <p id="variantsModalTitle" class="text-sm text-gray-500 mt-0.5"></p>
        </div>
        <button id="closeVariantsModal" class="text-gray-400 hover:text-gray-700 transition">
          <i class="fa-solid fa-times text-xl"></i>
        </button>
      </div>
      <div id="variantsModalContent" class="space-y-3">
        <!-- Variants loaded by JS -->
      </div>
    </div>
  </div>
</div>