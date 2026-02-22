<!-- Navbar -->
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="h-16 flex items-center justify-between">
            <!-- Desktop: nav left | Mobile: logo left -->
            <div class="flex-1 flex items-center">
                <!-- Desktop nav -->
                <nav class="hidden md:flex gap-6 font-semibold nav-links">
                    <a href="<?= page_url('home') ?>" class="hover:underline hover:font-bold cursor-pointer">Home</a>
                    <a href="<?= page_url('shop') ?>" class="hover:underline hover:font-bold cursor-pointer">Shop</a>
                    <div class="categories-container relative">
                        <a href="#" class="categories-dropdown-toggle hover:underline hover:font-bold cursor-pointer flex items-center gap-1">
                            Categories <i class="fas fa-chevron-down text-xs"></i>
                        </a>
                    </div>
                    <a href="<?= page_url('contact') ?>" class="hover:underline cursor-pointer hover:font-bold">Contact Us</a>
                </nav>
                <div class="md:hidden">
                    <a href="<?= page_url('home') ?>">
                        <?php
                            $logoSetting = getSetting('logo');
                            $logo = $logoSetting ? url($logoSetting) : asset('images/egologo3.png');
                        ?>
                        <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars(getSetting('website_name', 'EGO')) ?>" class="h-10 w-auto cursor-pointer">
                    </a>
                </div>
            </div>
            <!-- Desktop logo (centered absolutely) -->
            <div class="hidden md:block absolute left-1/2 -translate-x-1/2">
                <a href="<?= page_url('home') ?>">
                    <?php 
                        $logoSetting = getSetting('logo');
                        $logo = $logoSetting ? url($logoSetting) : asset('images/egologo3.png');
                    ?>
                    <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars(getSetting('website_name', 'EGO')) ?>" class="h-14 w-auto cursor-pointer">
                </a>
            </div>

            <!-- Right side (icons) -->
            <div class="flex-1 flex justify-end items-center gap-3 nav-icons">
                <!-- Desktop icons -->
                <?php if (Auth::check() && Auth::isCustomer()): ?>
                <div class="hidden md:inline-flex relative group">
                    <button class="items-center justify-center w-8 h-10 cursor-pointer inline-flex" aria-label="Account Menu">
                        <i class="fi fi-rr-user text-xl"></i>
                    </button>
                    <div class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="<?= page_url('account') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-t-lg">My Account</a>
                        <a href="<?= page_url('order-history') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">My Orders</a>
                        <a href="#" onclick="customerLogout(event)" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-50 rounded-b-lg border-t border-gray-100">Logout</a>
                    </div>
                </div>
                <?php else: ?>
                <button id="openLogin" class="hidden md:inline-flex items-center justify-center w-8 h-10 cursor-pointer" aria-label="Account">
                <i class="fi fi-rr-user text-xl"></i>
                </button>
                <?php endif; ?>
                <a href="<?= page_url('cart') ?>" class="hidden md:inline-flex items-center justify-center w-8 h-10 cursor-pointer relative" aria-label="Cart">
                    <i class="fi fi-rr-shopping-bag text-xl"></i>
                    <span id="cart-count-badge" class="absolute -top-1 -right-1 bg-brand text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold cart-count-display" style="display: none;">0</span>
                </a>

                <!-- Mobile icons + hamburger -->
                <div class="md:hidden flex items-center gap-2">
                    <?php if (Auth::check() && Auth::isCustomer()): ?>
                    <a href="<?= page_url('account') ?>" aria-label="Account"><i class="fi fi-rr-user text-xl"></i></a>
                    <?php else: ?>
                    <button id="openLoginPhone" aria-label="Account"><i class="fi fi-rr-user text-xl"></i></button>
                    <?php endif; ?>
                    <a href="<?= page_url('cart') ?>" aria-label="Cart" class="relative">
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
                <a href="<?= page_url('home') ?>" class="hover:text-gray-300">Home</a>
                <a href="<?= page_url('shop') ?>" class="hover:text-gray-300">Shop</a>
                <div>
                    <a href="#" class="mobile-categories-toggle hover:text-gray-300 flex items-center justify-between">
                        Categories <i class="fas fa-chevron-down text-xs"></i>
                    </a>
                    <!-- Mobile dropdown will be populated by JavaScript -->
                </div>
                <a href="<?= page_url('contact') ?>" class="hover:text-gray-300">Contact Us</a>
                <?php if (Auth::check() && Auth::isCustomer()): ?>
                <hr class="border-white/20">
                <a href="<?= page_url('account') ?>" class="hover:text-gray-300">My Account</a>
                <a href="<?= page_url('order-history') ?>" class="hover:text-gray-300">My Orders</a>
                <a href="#" onclick="customerLogout(event)" class="hover:text-gray-300 text-red-400">Logout</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

<?php if (Auth::check() && Auth::isCustomer()): ?>
<script>
function customerLogout(e) {
    e.preventDefault();
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    fetch('<?= url("api/logout-user.php") ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': csrfToken},
        body: 'csrf_token=' + encodeURIComponent(csrfToken)
    })
    .then(function(r) { return r.json(); })
    .then(function() { window.location.href = '<?= page_url("home") ?>'; })
    .catch(function() { window.location.href = '<?= page_url("home") ?>'; });
}
</script>
<?php endif; ?>