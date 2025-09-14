<?php   include __DIR__ . '/../../config/path.php';
        require_once  CORE . 'Helper.php';
?>

        <aside class="w-64 h-full bg-white text-black flex flex-col p-4 shadow-[0_0_18px_-4px_rgba(0,0,0,0.25)] ">
            <div class="flex flex-col flex-grow">
                <nav class="flex flex-col gap-2">
                    <?php
                        Helper::sidebarLink("dashboard",$action,"Dashboard","fa-house");
                        Helper::sidebarLink("orderManagement", $action, "Order Management", "fa-cart-shopping");
                    ?>
                
                </nav>
                <p class="mt-4 mb-2">Product</p>
                <nav class="flex flex-col gap-2">
                    <?php 
                        Helper::sidebarLink("addProduct", $action, "Add Product","fa-plus");
                        Helper::sidebarLink("Categories", $action, "Categories","fa-list");
                    ?>
                </nav>
                <P class="mt-4 mb-2">Users</P>
                <nav class="flex flex-col gap-2">
                    <?php
                        Helper::sidebarLink("Admins", $action, "Admins","fa-font");
                        Helper::sidebarLink("Customers", $action, "Customers","fa-circle-user");
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
    