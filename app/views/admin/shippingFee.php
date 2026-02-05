<div class="flex gap-4">
  <div class="w-[256px] h-[130px] bg-white p-4 shadow">
    <p class="text-l font-bold">Total Regions</p>
    <p class="text-3xl font-bold my-2" id="totalRegions">0</p>
    <p class="text-sm font-thin">Currently active</p>
  </div>
</div>

<div class="w-full bg-white flex flex-col items-start p-8 shadow mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Manage Shipping Regions</p>
    <button id="addRegionBtn" class="bg-brand text-white px-4 py-2 rounded hover:bg-opacity-90 cursor-pointer">
      + Add Region
    </button>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Region Name</th>
        <th>Fee per KG</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="regionTableBody"></tbody>
  </table>
</div>

<!-- Add/Edit Region Modal -->
<div id="regionModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 id="regionModalTitle" class="text-xl font-bold mb-4">Add Region</h2>
    <form id="regionForm" class="flex flex-col gap-4">
      <input type="hidden" name="id" id="regionId">
      <input type="text" name="region_name" id="regionName" placeholder="Region name" class="border rounded p-2 w-full outline-none" required>
      <input type="number" step="0.01" name="fee_per_kg" id="feePerKg" placeholder="Fee per KG" class="border rounded p-2 w-full outline-none" required>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeRegionModal" class="px-4 py-2 border rounded">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Confirm Delete -->
<div id="confirmDeleteModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[380px]">
    <h2 class="text-xl font-bold mb-4">Confirm Deletion</h2>
    <p id="confirmDeleteText" class="text-gray-700 mb-6"></p>
    <div class="flex justify-end gap-3">
      <button id="cancelDeleteBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
      <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
    </div>
  </div>
</div>
