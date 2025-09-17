<header class="relative isolate min-h-[70svh] md:min-h-[80svh]">
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

    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="h-16 flex items-center justify-between">
            <img src="assets/images/egologo2.png" alt="">

             <!-- LEFT (desktop links) -->
            <nav class="hidden md:flex items-center gap-6 text-white/90">
                <a class="hover:opacity-100">Home</a>
                <a class="hover:opacity-100">Shop</a>
                <a class="hover:opacity-100">Categories</a>
                <a class="hover:opacity-100">Contact us</a>
            </nav>

            <!-- Mobile menu button -->
             <div class="md:hidden flex items-center text-white">
                <button class="inline-flex items-center justify-center w-8 h-10 rounded-md" aria-label="Account">
                    <i class="fi fi-rr-user text-xl"></i>
                </button>
                <button class="inline-flex items-center justify-center w-8 h-10 rounded-md" aria-label="Cart">
                    <i class="fi fi-rr-shopping-bag text-xl"></i>
                </button>
                <button id="menuBtn" aria-controls="mobileNav" aria-expanded="false"
                        class="inline-flex items-center justify-center w-8 h-10 rounded-md" aria-label="Menu">
                    <i class="fi fi-rr-menu-burger"></i>
                </button>
            </div>
            
        </div>

            <!-- Mobile menu -->
            <nav id="mobileNav" class="md:hidden hidden mt-2 rounded-lg bg-black/50 backdrop-blur text-white">
                <div class="px-4 py-3 flex flex-col gap-3">
                    <a>Work</a><a>About</a><a>Contact</a>
                </div>
            </nav>
            <!-- HERO SECTION -->
            <div class="absolute inset-x-0 top-40">
                <div class="w-full max-w-screen-xl mx-auto px-4 sm:px-6">
                    <div class="max-w-xl text-center  text-white drop-shadow">
                        
                        <h1 class="font-outfit font-bold leading-tight text-[clamp(2rem,5vw,3rem)]">
                            <?php echo $header_title ?? 'Ego-Luxury' ?>
                        </h1>
                        <p class="mt-1 text-white text-[clamp(1rem,2.2vw,1.25rem)]">
                            <?php echo $header_subtitle ?? '' ?>
                        </p>
                    </div>
                </div>
            </div>
    </div>
    
</header>