<header class="relative isolate min-h-[50svh] md:min-h-[120svh]">
    <div class="absolute inset-0 -z-10">
        <img 
            src="<?php echo $header_bg ?? 'assets/images/header2.php';?>" 
            alt="Fashion header"
            class="h-full w-full object-cover"
            loading="eager"
            fetchpriority="high"
            />
        <div class="pointer-events-none absolute inset-x-0 -bottom-1 h-18 bg-gradient-to-t from-white to-transparent"></div>
    </div>

    <!-- Navbar -->
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="h-16 flex items-center justify-between">
            <!-- Desktop: nav left | Mobile: logo left -->
            <div class="flex-1 flex items-center">
                <!-- Desktop nav -->
                 <nav class="hidden md:flex gap-6 text-white/90">
                    <a href="index.php" class="hover:underline hover:font-bold cursor-pointer">Home</a>
                    <a href="shop.php" class="hover:underline hover:font-bold cursor-pointer">Shop</a>
                    <a class="hover:underline hover:font-bold cursor-pointer">Categories</a>
                    <a href="contact.php" class="hover:underline cursor-pointer hover:font-bold">Contact Us</a>
                </nav>
                <div class="md:hidden">
                    <img src="assets/images/egologo2.png" alt="EGO" class="h-10 w-auto">
                </div>
            </div>
            <!-- Desktop logo (centered absolutely) -->
            <div class="hidden md:block absolute left-1/2 -translate-x-1/2">
                <img src="assets/images/egologo2.png" alt="EGO" class="h-10 w-auto">
            </div>

             <!-- Right side (icons) -->
            <div class="flex-1 flex justify-end items-center gap-3 text-white">
                <!-- Desktop icons -->
                <button id="openLogin" class="hidden md:inline-flex items-center justify-center w-8 h-10 cursor-pointer" aria-label="Account">
                <i class="fi fi-rr-user text-xl"></i>
                </button>
                <button class="hidden md:inline-flex items-center justify-center w-8 h-10 cursor-pointer" aria-label="Cart">
                <i class="fi fi-rr-shopping-bag text-xl"></i>
                </button>

                <!-- Mobile icons + hamburger -->
                <div class="md:hidden flex items-center gap-2">
                    <button id="openLoginPhone" aria-label="Account"><i class="fi fi-rr-user text-xl"></i></button>
                    <button aria-label="Cart"><i class="fi fi-rr-shopping-bag text-xl"></i></button>
                    <button id="openSidebar" aria-controls="mobileNav" aria-expanded="false" aria-label="Menu">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <nav id="mobileNav" class="hidden md:hidden mt-2 rounded-lg bg-black/70 backdrop-blur text-white">
            <div class="px-4 py-3 flex flex-col gap-3">
                <a>Home</a>
                <a>Shop</a>
                <a>Categories</a>
                <a>Contact Us</a>
            </div>
        </nav>
  </div>

  <!-- Hero text -->
  <div class="absolute inset-0 flex flex-col items-center justify-center">
    <h1 class="text-5xl md:text-8xl font-normal font-cor text-white drop-shadow">
      <?php echo $header_title ?? "" ?>
    </h1>
    <h3 class="text-sm md:text-3xl font-outfit font-thin text-white drop-shadow mt-2"><?php echo $header_subtitle ?? ""?></h3>
  </div>
</header>