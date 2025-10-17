<div class="flex gap-4">
  <div class="w-[256px] h-[130px] bg-white p-4 shadow">
    <p class="text-l font-bold text-black">Total Coupons</p>
    <p class="text-3xl font-bold my-2" id="totalCoupons">0</p>
    <p class="text-sm font-thin text-black">Currently in database</p>
  </div>
</div>

<!-- COUPONS TABLE -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Manage Coupons</p>
    <button id="addCouponBtn" class="bg-brand text-white px-4 py-2 rounded hover:bg-opacity-90 cursor-pointer">
      + Add Coupon
    </button>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Code</th>
        <th>Discount Type</th>
        <th>Value</th>
        <th>Start</th>
        <th>End</th>
        <th>Min Order Value</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="couponTableBody"></tbody>
  </table>
</div>

<!-- ADD / EDIT COUPON MODAL -->
<div id="couponModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[450px]">
    <h2 id="couponModalTitle" class="text-xl font-bold mb-4">Add Coupon</h2>
    <form id="couponForm" class="flex flex-col gap-4">
      <input type="hidden" name="id" id="couponId">
      <input type="text" name="code" id="couponCode" placeholder="Coupon Code" class="border rounded p-2 w-full outline-none" required>
      <select name="discount_type" id="discountType" class="border rounded p-2 w-full outline-none">
        <option value="">Select Discount Type</option>
        <option value="percentage">Percentage</option>
        <option value="fixed">Fixed</option>
      </select>
      <input type="number" name="discount_value" id="discountValue" placeholder="Discount Value" class="border rounded p-2 w-full outline-none" required>
      <div class="flex gap-2">
        <input type="date" name="start_date" id="startDate" class="border rounded p-2 w-full outline-none" required>
        <input type="date" name="end_date" id="endDate" class="border rounded p-2 w-full outline-none" required>
      </div>
      <input type="number" name="min_order_value" id="minOrderValue" placeholder="Min Order Value" class="border rounded p-2 w-full outline-none">
      <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="isActive" class="w-4 h-4 border rounded">
        <label for="isActive">Active</label>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeCouponModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- CONFIRM DELETE -->
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
