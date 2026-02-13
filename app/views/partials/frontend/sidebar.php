
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 hidden z-40"></div>
<aside id="mobileSidebar" 
        class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
  
  <!-- Close button -->
  <div class="flex justify-end p-4 border-b relative z-10">
    <button id="closeSidebar" 
            type="button"
            class="inline-flex items-center justify-center text-gray-600 hover:text-gray-900 text-3xl font-light leading-none cursor-pointer" 
            aria-label="Close">&times;</button>
  </div>

  <!-- Navigation -->
  <nav class="px-4 space-y-2 text-start" id="mobileNav">
    <a href="<?= page_url('home') ?>" class="block py-2 border-b font-semibold">Home</a>
    <a href="<?= page_url('shop') ?>" class="block py-2 border-b font-semibold">Shop</a>

    <!-- Categories with Arrow -->
    <div class="border-b">
      <button class="mobile-categories-toggle w-full flex items-center justify-between py-2">
        <span class="font-semibold">Categories</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
      </button>
    </div>
    
    <!-- Categories Dropdown - Will be populated by JavaScript -->
    <div class="mobile-categories-dropdown hidden" id="mobileCategoriesDropdown"></div>

    <a href="<?= page_url('contact') ?>" class="block py-2 border-b font-semibold">Contact us</a>
  </nav>
</aside>