<?php
    // Start output buffering to prevent header issues
    ob_start();
    
    require_once __DIR__ . '/../../app/config/path.php';
    require_once CONT . 'AdminController.php';
    require_once CORE . 'Session.php';
    require_once CORE . 'Helper.php';
    require_once CORE . 'View.php';

    
    // Set session timeout to 15 minutes (900 seconds) for admin panel
    Session::configure(900, url('admin/login.php'), false);
    Session::startSession();

    $adminController = new AdminController();

    $action = $_GET['action'] ?? 'dashboard';

    if ($action === 'logout') {
        ob_end_clean(); 
        $adminController->logout();
        exit;
    }

    if (!in_array($action, ['login', 'logout'])) {
        Auth::checkAdmin(); 
    }

    $roleRequiredActions = [
        'Admins' => ['super_admin'],
        'Settings' => ['super_admin'],
    ];

    if(isset($roleRequiredActions[$action])){
        Auth::checkRoles($roleRequiredActions[$action]);
    }

    $route = [
        'dashboard'       => fn() => $adminController->dashboard(),
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
    


