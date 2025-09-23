
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 hidden z-40"></div>
<aside id="mobileSidebar" 
       class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
  
  <!-- Close button -->
  <div class="flex justify-end p-4">
    <button id="closeSidebar" class="text-2xl">&times;</button>
  </div>

  <!-- Navigation -->
  <nav class="px-4 space-y-2 text-start">
    <a href="index.php" class="block py-2 border-b font-semibold">Home</a>
    <a href="shop.php" class="block py-2 border-b font-semibold">Shop</a>

    <!-- Dropdown -->
    <div>
      <button id="toggleCategories"
              class="flex items-center justify-between w-full py-2 border-b">
        <span class="font-semibold">Categories</span>
        <span id="arrow" class="ml-2">▼</span>
      </button>

      <div id="categoriesMenu" class="hidden pl-4 space-y-2">
        <a href="#" class="block py-2 border-b font-thin">Jeans</a>
        <a href="#" class="block py-2 border-b font-thin">Sets</a>
        <a href="#" class="block py-2 border-b font-thin">Tops</a>
        <a href="#" class="block py-2 border-b font-thin">Coats</a>
      </div>
    </div>

    <a href="contact.php" class="block py-2 border-b font-semibold">Contact us</a>
  </nav>
</aside>