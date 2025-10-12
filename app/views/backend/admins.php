<div class="flex gap-4">
  <!-- Summary Cards -->
  <div class="w-[256px] h-[130px] bg-white p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)]">
    <p class="text-l font-bold text-black">Total Admins</p>
    <p class="text-3xl font-bold text-black my-2" id="totalAdmins">0</p>
    <p class="text-sm font-thin text-black">Currently Active</p>
  </div>
</div>

<!-- ADMINS SECTION -->
<div class="w-full bg-white flex flex-col items-start p-8 shadow-[0_0_18.2px_-4px_rgba(0,0,0,0.25)] mt-8">
  <div class="w-full flex justify-between mb-8">
    <p class="text-2xl font-bold">Manage Admins</p>
    <button id="addAdminBtn" class="bg-brand text-white font-semibold px-4 py-2 rounded hover:bg-opacity-90 cursor-pointer">+ Add New Admin</button>
  </div>

  <table class="table-auto w-full md:table-fixed">
    <thead class="bg-[rgba(240,215,186,0.2)]">
      <tr>
        <th class="pt-4 pb-4">#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="adminTableBody">
      <!-- Loaded dynamically -->
    </tbody>
  </table>
</div>

<!-- ADD ADMIN MODAL -->
<div id="addAdminModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Add New Admin</h2>
    <form id="addAdminForm" class="flex flex-col gap-4">
      <input type="text" name="name" placeholder="Full name" class="border rounded p-2 w-full outline-none" required>
      <input type="email" name="email" placeholder="Email" class="border rounded p-2 w-full outline-none" required>
      <input type="password" name="password" placeholder="Password" class="border rounded p-2 w-full outline-none" required>
      <select name="role" class="border rounded p-2 w-full outline-none" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="super_admin">Super Admin</option>
      </select>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeAddAdminModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-brand text-white rounded cursor-pointer">Add</button>
      </div>
    </form>
  </div>
</div>

<!-- EDIT ADMIN MODAL -->
<div id="editAdminModal" class="hidden fixed inset-0 flex items-center justify-center bg-white/20 backdrop-blur-md z-50">
  <div class="bg-white p-6 rounded shadow-lg w-[400px]">
    <h2 class="text-xl font-bold mb-4">Edit Admin</h2>
    <form id="editAdminForm" class="flex flex-col gap-4">
      <input type="hidden" name="id" id="editAdminId">
      <input type="text" name="name" id="editAdminName" placeholder="Full name" class="border rounded p-2 w-full outline-none" required>
      <input type="email" name="email" id="editAdminEmail" placeholder="Email" class="border rounded p-2 w-full outline-none" required>
      <select name="role" id="editAdminRole" class="border rounded p-2 w-full outline-none" required>
        <option value="admin">Admin</option>
        <option value="super_admin">Super Admin</option>
      </select>
      <div class="flex justify-end gap-2">
        <button type="button" id="closeEditAdminModal" class="px-4 py-2 border rounded cursor-pointer">Cancel</button>
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
      Are you sure you want to delete this admin?
    </p>
    <div class="flex justify-end gap-3">
      <button id="cancelDeleteBtn" class="px-4 py-2 border rounded hover:bg-gray-100 cursor-pointer">Cancel</button>
      <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 cursor-pointer">Delete</button>
    </div>
  </div>
</div>