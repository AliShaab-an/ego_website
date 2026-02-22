<div class="flex gap-4">
  <!-- Summary Cards -->
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Orders</p>
    <p class="text-3xl font-bold text-black my-2" id="totalOrders">0</p>
    <p class="text-sm font-thin text-black">All time</p>
  </div>
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Pending Orders</p>
    <p class="text-3xl font-bold text-black my-2" id="pendingOrders">0</p>
    <p class="text-sm font-thin text-black">Needs attention</p>
  </div>
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Completed Orders</p>
    <p class="text-3xl font-bold text-black my-2" id="completedOrders">0</p>
    <p class="text-sm font-thin text-black">Fulfilled</p>
  </div>
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Revenue</p>
    <p class="text-3xl font-bold text-black my-2" id="totalRevenue">$0</p>
    <p class="text-sm font-thin text-black">All time</p>
  </div>
</div>

<!-- ORDERS SECTION -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Orders Management</p>
    <div class="flex gap-2">
      <!-- Status Filter -->
      <select id="statusFilter" class="border rounded px-3 py-2 outline-none">
        <option value="all">All Orders</option>
        <option value="pending">Pending</option>
        <option value="shipped">Shipped</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>
  </div>

  <table class="table-fixed w-full">
    <colgroup>
      <col style="width:4%">
      <col style="width:7%">
      <col style="width:17%">
      <col style="width:5%">
      <col style="width:8%">
      <col style="width:10%">
      <col style="width:9%">
      <col style="width:10%">
      <col style="width:10%">
      <col style="width:20%">
    </colgroup>
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4 text-center">#</th>
        <th class="text-center">Order ID</th>
        <th class="text-left pl-3">Customer</th>
        <th class="text-center">Items</th>
        <th class="text-center">Total</th>
        <th class="text-center">Payment</th>
        <th class="text-center">Pay Type</th>
        <th class="text-center">Status</th>
        <th class="text-center">Date</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody id="ordersTableBody">
      <!-- Loaded via AJAX -->
    </tbody>
  </table>

  <div class="flex justify-between w-full mt-4 px-4">
    <button id="prevPage" class="px-3 py-1 bg-white border rounded shadow cursor-pointer" disabled>Previous</button>
    <span id="pageInfo" class="text-gray-600">Page 1</span>
    <button id="nextPage" class="px-3 py-1 bg-white border rounded shadow cursor-pointer">Next</button>
  </div>
</div>

<!-- VIEW ORDER MODAL -->
<div id="viewOrderModal" class="hidden fixed inset-0 bg-white/20 backdrop-blur-md z-50">
  <div class="flex items-center justify-center h-full">
    <div class="bg-white p-6 rounded shadow-lg w-[800px] max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-900">Order Details</h2>
        <button id="closeViewOrderModal" class="text-gray-500 hover:text-gray-700">
          <i class="fa-solid fa-times text-xl"></i>
        </button>
      </div>
      
      <div id="orderDetails" class="space-y-4">
        <!-- Order details will be loaded here -->
      </div>
    </div>
  </div>
</div>

<!-- UPDATE STATUS MODAL -->
<div id="updateStatusModal" class="hidden fixed inset-0 bg-white/20 backdrop-blur-md z-50">
  <div class="flex items-center justify-center h-full">
    <div class="bg-white p-6 rounded shadow-lg w-[400px]">
      <h2 class="text-xl font-bold mb-4 text-gray-900">Update Order Status</h2>
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
          <select id="orderStatusSelect" class="w-full border rounded px-3 py-2">
            <option value="pending">Pending</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
          <select id="paymentStatusSelect" class="w-full border rounded px-3 py-2">
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
          </select>
        </div>
      </div>
      
      <div class="flex justify-end gap-3 mt-6">
        <button id="cancelUpdateBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
        <button id="confirmUpdateBtn" class="px-4 py-2 bg-brand text-white rounded hover:bg-opacity-90 cursor-pointer">Update</button>
      </div>
    </div>
  </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteOrderModal" class="hidden fixed inset-0 bg-white/20 backdrop-blur-md z-50">
  <div class="flex items-center justify-center h-full">
    <div class="bg-white p-6 rounded shadow-lg w-[400px]">
      <h2 class="text-xl font-bold mb-2 text-gray-900">Delete Order</h2>
      <p class="text-gray-600 mb-6">Are you sure you want to permanently delete order <strong id="deleteOrderId"></strong>? This cannot be undone.</p>
      <div class="flex justify-end gap-3">
        <button id="cancelDeleteBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
        <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- SUCCESS/ERROR MESSAGES -->
<div id="messageContainer" class="fixed top-4 right-4 z-50"></div>
