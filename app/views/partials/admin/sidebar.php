<?php $action = $action ?? ($_GET['action'] ?? 'dashboard'); ?>
<aside class="w-64 h-screen bg-white text-black flex flex-col shadow-lg border-r border-gray-200">
    <!-- Header -->
    <div class="p-4 border-b border-gray-200 flex-shrink-0">
        <h1 class="text-xl font-bold text-gray-800">Ego Admin</h1>
    </div>
            
    <!-- Navigation - Scrollable -->
    <nav class="flex-1 overflow-y-auto p-2">
        <div class="flex flex-col gap-1">
            <?php
                Helper::sidebarLink("dashboard",$action,"Dashboard","fa-house");
                Helper::sidebarLink("orderManagement", $action, "Order Management", "fa-cart-shopping");
                Helper::sidebarLink("addProduct", $action, "Add Product","fa-plus");
                Helper::sidebarLink("manageProducts", $action, "Products","fa-shirt");
                Helper::sidebarLink("Categories", $action, "Categories","fa-list");
                Helper::sidebarLink("Admins", $action, "Admins","fa-font");
                Helper::sidebarLink("ColorsAndSizes", $action, "Colors & Sizes","fa-palette");
                Helper::sidebarLink("ShippingFees", $action, "Shipping Fees","fa-truck-fast");
                Helper::sidebarLink("Coupons", $action, "Coupons","fa-ticket");
                Helper::sidebarLink("Newsletter", $action, "Newsletter","fa-envelope");
                Helper::sidebarLink("ContactMessages", $action, "Contact Messages","fa-message");
                Helper::sidebarLink("Settings", $action, "Settings","fa-gear");
            ?>
        </div>
    </nav>
            
    <!-- User Info - Fixed at bottom -->
    <div class="p-4 border-t border-gray-200 flex-shrink-0">
        <p class="font-semibold text-gray-800 text-sm truncate"><?= $_SESSION['user_name'] ?></p>
        <div class="flex items-center gap-2 mt-2">
            <p class="truncate text-xs text-gray-600"><?= $_SESSION['user_email'] ?></p>
            <a href="index.php?action=logout" class="text-gray-500 hover:text-red-500 transition text-lg ml-auto">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</aside>
    