<div id="createAdmin" class="fixed inset-0 backdrop-blur-md bg-white/20 flex items-center justify-center hidden z-[999px]">
    <div class="bg-white rounded-lg p-6 w-md relative">
        <button id="AdminCloseBtn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <h2 class="text-xl font-bold mb-4">Add Admin</h2>
        <form id="addAdminForm" class="w-full" >
            <label for="adminName" class="text-lg font-bold">Name</label>
            <input id="adminName" name="name" type="text" class="w-full p-2 border border-gray-500 rounded my-2">
            <label for="adminEmail" class="text-lg font-bold">Email</label>
            <input id="adminEmail" name="email" type="email" class="w-full p-2 border border-gray-500 rounded my-2">
            <label for="adminPassword" class="text-lg font-bold">Password</label>
            <div class="relative">
                <input id="adminPassword" name="password" type="password" class="w-full p-2 border border-gray-500 rounded my-2">
                <button id="togglePassword" type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 cursor-pointer">
                <i id="toggleIcon" class="fa fa-eye"></i>
                </button>
            </div>
            
            <button id="AddAdminBtn" class="px-4 py-2 bg-white border border-black text-black rounded hover:bg-[rgba(183,146,103,1)] hover:text-white hover:border-none transition">Submit</button> 
        </form>             
    </div>
</div>