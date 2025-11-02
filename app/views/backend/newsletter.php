<div class="flex gap-4">
  <!-- Summary Cards -->
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Subscribers</p>
    <p class="text-3xl font-bold text-black my-2" id="totalSubscribers">0</p>
    <p class="text-sm font-thin text-black">All time</p>
  </div>
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Active Subscribers</p>
    <p class="text-3xl font-bold text-black my-2" id="activeSubscribers">0</p>
    <p class="text-sm font-thin text-black">Currently subscribed</p>
  </div>
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Unsubscribed</p>
    <p class="text-3xl font-bold text-black my-2" id="unsubscribedCount">0</p>
    <p class="text-sm font-thin text-black">Total unsubscribed</p>
  </div>
</div>

<!-- NEWSLETTER SUBSCRIBERS SECTION -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Newsletter Subscribers</p>
    <div class="flex gap-2">
      <!-- Status Filter -->
      <select id="statusFilter" class="border rounded px-3 py-2 outline-none">
        <option value="all">All Status</option>
        <option value="active">Active</option>
        <option value="unsubscribed">Unsubscribed</option>
      </select>
      <!-- Export Button -->
      <button id="exportCsvBtn" class="bg-green-600 text-white font-semibold px-4 py-2 rounded hover:bg-green-700 cursor-pointer">
        <i class="fa-solid fa-download mr-2"></i>Export CSV
      </button>
    </div>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Registered User</th>
        <th>Subscribed Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="newsletterTableBody">
      <!-- Loaded via AJAX -->
    </tbody>
  </table>

  <div class="flex justify-between w-full mt-4 px-4">
    <button id="prevPage" class="px-3 py-1 bg-white rounded shadow cursor-pointer" disabled>Previous</button>
    <span id="pageInfo" class="text-gray-600">Page 1</span>
    <button id="nextPage" class="px-3 py-1 bg-white rounded shadow cursor-pointer">Next</button>
  </div>
</div>

<!-- CONFIRM ACTION MODAL -->
<div id="confirmActionModal" class="hidden fixed inset-0 bg-white/20 backdrop-blur-md z-50">
  <div class="flex items-center justify-center h-full">
    <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4 text-gray-900" id="confirmActionTitle">Confirm Action</h2>
    <p id="confirmActionText" class="text-gray-700 mb-6">
      Are you sure you want to perform this action?
    </p>
    <div class="flex justify-end gap-3">
      <button id="cancelActionBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
      <button id="confirmActionBtn" class="px-4 py-2 bg-brand text-white rounded hover:bg-opacity-90 cursor-pointer">Confirm</button>
    </div>
    </div>
  </div>
</div>

<!-- SUCCESS/ERROR MESSAGES -->
<div id="messageContainer" class="fixed top-4 right-4 z-50"></div>
