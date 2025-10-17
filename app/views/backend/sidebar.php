<?php   

    require_once __DIR__ . '/../../config/path.php';
    require_once  CORE . 'Helper.php';
?>

        <aside class="w-64 h-full bg-white text-black flex flex-col p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)] space-y-2">
            <div class="flex flex-col flex-grow">
                <nav class="flex flex-col gap-2">
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
                    ?>
                </nav>
                
            </div>
            <!-- Logout button at bottom -->
            <div class="mt-auto pt-4">
                <p class="font-bold"><?= $_SESSION['user_name'] ?></p>
                <div class="flex items-center gap-2  rounded">
                    <p class="truncate"><?= $_SESSION['user_email'] ?></p>
                    <a href="index.php?action=logout"><i class="fa-solid fa-right-from-bracket hover:text-red-500 text-lg"></i></a>
                </div>
            </div>
        </aside>
    