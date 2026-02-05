<div class="flex gap-4">
  <!-- Summary Cards -->
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Colors</p>
    <p class="text-3xl font-bold text-black my-2" id="totalColors">0</p>
    <p class="text-sm font-thin text-black">Currently in database</p>
  </div>
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Sizes</p>
    <p class="text-3xl font-bold text-black my-2" id="totalSizes">0</p>
    <p class="text-sm font-thin text-black">Currently in database</p>
  </div>
</div>

<!-- COLORS SECTION -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Manage Colors</p>
    <button id="addColorBtn" class="bg-brand text-white font-semibold px-4 py-2 rounded hover:bg-opacity-90 cursor-pointer">+ Add New Color</button>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Color Name</th>
        <th>Hex Code</th>
        <th>Preview</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="colorTableBody">
      
    </tbody>
  </table>

  <div class="flex justify-between w-full mt-4 px-4">
    <button id="prevPage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Previous</button>
    <button id="nextPage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Next</button>
  </div>
</div>

<!-- SIZES SECTION -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Manage Sizes</p>
    <button id="addSizeBtn" class="bg-brand text-white font-semibold px-4 py-2 rounded hover:bg-opacity-90 cursor-pointer">+ Add New Size</button>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Size Name</th>
        <th>Type</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="sizeTableBody">
      
    </tbody>
  </table>

  <div class="flex justify-between w-full mt-4 px-4">
    <button id="prevSizePage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Previous</button>
    <button id="nextSizePage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Next</button>
  </div>
  <p class="text-center text-gray-500 mt-2" id="pageInfo"></p>
</div>

<!-- ADD COLOR MODAL -->
<div id="addColorModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Add New Color</h2>
    <form id="addColorForm" class="flex flex-col gap-4">
      <input type="text" name="name" placeholder="Color name" class="border rounded p-2 w-full outline-none" required>
      <input type="color" name="hex_code" class="border rounded w-20 h-10 cursor-pointer">
      <div class="flex justify-end gap-2">
        <button type="button" id="closeColorModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT COLOR MODAL -->
<div id="editColorModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Edit Color</h2>
    <form id="editColorForm" class="flex flex-col gap-4">
      <input type="hidden" name="id" id="editColorId">
      <input type="text" name="name" id="editColorName" placeholder="Color name"
        class="border rounded p-2 w-full outline-none" required>
      <input type="color" name="hex_code" id="editColorHex"
        class="border rounded w-20 h-10 cursor-pointer">
      <div class="flex justify-end gap-2">
        <button type="button" id="closeEditColorModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- ADD SIZE MODAL -->
<div id="addSizeModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Add New Size</h2>
    <form id="addSizeForm" class="flex flex-col gap-4">
      <input type="text" name="name" placeholder="Size name" class="border rounded p-2 w-full outline-none" required>
      <input type="text" name="type" placeholder="Type (e.g. Clothing, Shoe)" class="border rounded p-2 w-full outline-none">
      <div class="flex justify-end gap-2">
        <button type="button" id="closeSizeModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT SIZE MODAL -->
<div id="editSizeModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Edit Size</h2>
    <form id="editSizeForm" class="flex flex-col gap-4">
      <input type="hidden" name="id" id="editSizeId">
      <input type="text" name="name" id="editSizeName" placeholder="Size name"
        class="border rounded p-2 w-full outline-none" required>
      <input type="text" name="type" id="editSizeType" placeholder="Type (e.g. Clothing, Shoe)"
        class="border rounded p-2 w-full outline-none" required>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeEditSizeModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
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
      Are you sure you want to delete this item?
    </p>
    <div class="flex justify-end gap-3">
      <button id="cancelDeleteBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
      <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
    </div>
  </div>
</div>


<div id="confirmDeleteSizeModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[380px]">
    <h2 class="text-xl font-bold mb-4 text-gray-900">Confirm Deletion</h2>
    <p id="confirmDeleteSizeText" class="text-gray-700 mb-6">
      Are you sure you want to delete this item?
    </p>
    <div class="flex justify-end gap-3">
      <button id="cancelDeleteSizeBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
      <button id="confirmDeleteSizeBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
    </div>
  </div>
</div>
