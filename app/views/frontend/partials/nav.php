<!-- Navbar -->
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="h-16 flex items-center justify-between">
            <!-- Desktop: nav left | Mobile: logo left -->
            <div class="flex-1 flex items-center">
                <!-- Desktop nav -->
                 <nav class="hidden md:flex gap-6 font-semibold">
                    <a href="index.php" class="hover:underline hover:font-bold cursor-pointer">Home</a>
                    <a href="shop.php" class="hover:underline hover:font-bold cursor-pointer">Shop</a>
                    <div class="categories-container relative">
                        <a href="#" class="categories-dropdown-toggle hover:underline hover:font-bold cursor-pointer flex items-center gap-1">
                            Categories <i class="fas fa-chevron-down text-xs"></i>
                        </a>
                        <!-- Dropdown will be populated by JavaScript -->
                    </div>
                    <a href="contact.php" class="hover:underline cursor-pointer hover:font-bold">Contact Us</a>
                </nav>
                <div class="md:hidden">
                    <img src="<?php echo $nav_logo ?? 'assets/images/egologo3.php';?>" alt="EGO" class="h-10 w-auto">
                </div>
            </div>
            <!-- Desktop logo (centered absolutely) -->
            <div class="hidden md:block absolute left-1/2 -translate-x-1/2">
                <img src=<?php echo $nav_logo ?? 'assets/images/egologo3.php';?> alt="EGO" class="h-14 w-auto">
            </div>

             <!-- Right side (icons) -->
            <div class="flex-1 flex justify-end items-center gap-3 nav-icons">
                <!-- Desktop icons -->
                <button id="openLogin" class="hidden md:inline-flex items-center justify-center w-8 h-10 cursor-pointer" aria-label="Account">
                <i class="fi fi-rr-user text-xl"></i>
                </button>
                <a href="cart.php" class="hidden md:inline-flex items-center justify-center w-8 h-10 cursor-pointer relative" aria-label="Cart">
                    <i class="fi fi-rr-shopping-bag text-xl"></i>
                    <span id="cart-count-badge" class="absolute -top-1 -right-1 bg-brand text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold cart-count-display" style="display: none;">0</span>
                </a>

                <!-- Mobile icons + hamburger -->
                <div class="md:hidden flex items-center gap-2">
                    <button id="openLoginPhone" aria-label="Account"><i class="fi fi-rr-user text-xl"></i></button>
                    <a href="cart.php" aria-label="Cart" class="relative">
                        <i class="fi fi-rr-shopping-bag text-xl"></i>
                        <span id="cart-count-badge-mobile" class="absolute -top-1 -right-1 bg-brand text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold cart-count-display" style="display: none;">0</span>
                    </a>
                    <button id="openSidebar" aria-controls="mobileNav" aria-expanded="false" aria-label="Menu">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <nav id="mobileNav" class="hidden md:hidden mt-2 rounded-lg bg-black/70 backdrop-blur text-white">
            <div class="px-4 py-3 flex flex-col gap-3">
                <a href="index.php" class="hover:text-gray-300">Home</a>
                <a href="shop.php" class="hover:text-gray-300">Shop</a>
                <div>
                    <a href="#" class="mobile-categories-toggle hover:text-gray-300 flex items-center justify-between">
                        Categories <i class="fas fa-chevron-down text-xs"></i>
                    </a>
                    <!-- Mobile dropdown will be populated by JavaScript -->
                </div>
                <a href="contact.php" class="hover:text-gray-300">Contact Us</a>
            </div>
        </nav>
  </div>