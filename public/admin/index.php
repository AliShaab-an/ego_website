<?php
    
    require_once __DIR__ . '/../../app/bootstrap.php';

    Middleware::requireRoles(['admin', 'super_admin','editor']);

    $adminController = new AdminController();
    $settings = new SettingsController();
    $categories = new CategoryController();

    $action = $_GET['action'] ?? 'dashboard';

    $route = [

        'dashboard'       => fn() => $adminController->dashboard(),
        'logout'          => fn() => $adminController->logout(),

        'orderManagement' => fn() => $adminController->ordersPage(),
        'addProduct'      => fn() => $adminController->productsPage(),
        'Categories'      => fn() => $adminController->categoryPage(),
        'Admins'          => fn() => $adminController->adminsPage(),
        'ColorsAndSizes'  => fn() => $adminController->colorsAndSizesPage(),
        'ShippingFees'    => fn() => $adminController->shippingPage(),
        'Coupons'         => fn() => $adminController->couponsPage(),
        'manageProducts'  => fn() => $adminController->manageProducts(),
        'Newsletter'      => fn() => $adminController->newsletterPage(),
        'ContactMessages' => fn() => $adminController->contactMessagesPage(),
        'Settings'        => fn() => $adminController->settingsPage(),
    ];

    if($action == 'login'){
        require __DIR__ . '/login.php';
        exit;
    }

    $pageTitle = "Admin Panel - Ego Clothing";

    if (!isset($route[$action])) {
        View::render('admin/404', compact('action', 'pageTitle'), 'layouts/admin');
        exit;
    }

    $route[$action]();
    exit;
?>
    


