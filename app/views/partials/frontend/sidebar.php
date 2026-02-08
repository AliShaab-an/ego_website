
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 hidden z-40"></div>
<aside id="mobileSidebar" 
        class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
  
  <!-- Close button -->
  <div class="flex justify-end p-4">
    <button id="closeSidebar" class="text-2xl">&times;</button>
  </div>

  <!-- Navigation -->
  <nav class="px-4 space-y-2 text-start" id="mobileNav">
    <a href="index.php" class="block py-2 border-b font-semibold">Home</a>
    <a href="index.php?page=shop" class="block py-2 border-b font-semibold">Shop</a>

    <!-- Categories with Arrow -->
    <div class="border-b">
      <button class="mobile-categories-toggle w-full flex items-center justify-between py-2">
        <span class="font-semibold">Categories</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
      </button>
    </div>
    
    <!-- Categories Dropdown - Will be populated by JavaScript -->
    <div class="mobile-categories-dropdown hidden" id="mobileCategoriesDropdown"></div>

    <a href="index.php?page=contact" class="block py-2 border-b font-semibold">Contact us</a>
  </nav>
</aside>