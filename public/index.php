<?php
    require_once __DIR__ . '/../app/bootstrap.php';
    

    $frontend = new FrontendController();


    $page = $_GET['page'] ?? 'home';

    // ============================================================================
    // FRONTEND ROUTING with Authorization Guards
    // ============================================================================
    // PUBLIC: home, shop, product, category, contact (no guards)
    // CART: guests + customers allowed, admins blocked (allowGuestOrCustomer)
    // CHECKOUT: customers only, guests redirected to login, admins blocked (requireCustomer)
    // ============================================================================

    $routes = [
        // Public pages - accessible to everyone
        'home' => [
            'guard' => null,
            'run' => fn() => $frontend->home(),
        ],
        'shop' => [
            'guard' => null,
            'run' => fn() => $frontend->shop(),
        ],
        'product' => [
            'guard' => null,
            'run' => fn() => $frontend->product(),
        ],
        'category' => [
            'guard' => null,
            'run' => fn() => $frontend->category(),
        ],
        'contact' => [
            'guard' => null,
            'run' => fn() => $frontend->contact(),
        ],

        // Cart - guests and customers only (admins blocked)
        'cart' => [
            'guard' => fn() => Authorization::allowGuestOrCustomer(),
            'run' => fn() => $frontend->cart(),
        ],
        
        // Checkout - customers only (guests redirect to login, admins blocked)
        'checkout' => [
            'guard' => fn() => Authorization::requireCustomer(),
            'run' => fn() => $frontend->checkout(),
        ],
    ];

    // Route not found
    if(!isset($routes[$page])) {
        View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
        exit;
    }

    // Apply authorization guard if defined
    if (!empty($routes[$page]['guard'])) {
        $routes[$page]['guard']();
    }

    // Execute route handler
    $routes[$page]['run']();
    exit;

    // $header_bg = "assets/images/header2.png";
    // $header_title = "EGO Luxury";
    // $header_subtitle = "Modern Chick &amp; Timeless Elegance";
    // $nav_logo = "assets/images/egologo2.png";

    // $productController = new ProductController();
    // $topProducts = $productController->getTopProducts();
    // $newProducts = $productController->getNewProducts();
    // $shopTheLookProducts = $productController->getProductsByCategoryName('Shop the Look', 8);
    
    // $categoriesWithProducts = Category::getCategoriesWithProducts(4);
    // $settings = $settingController->getSettings();
    // $data = [
    //     'pageKey' => 'home',
    //     'header_bg' => "assets/images/header2.png",
    //     'header_title' => "EGO Luxury",
    //     'header_subtitle' => "Modern Chick &amp; Timeless Elegance",
    //     'nav_logo' => "assets/images/egologo2.png",

    //     // data needed in partials
    //     'topProducts' => $topProducts,
    //     'newProducts' => $newProducts,
    //     'shopTheLookProducts' => $shopTheLookProducts,
    //     'categoriesWithProducts' => $categoriesWithProducts,

    //     // SEO (optional)
    //     'metaTitle' => $settings('meta_title') ?: 'Ego Clothing',
    //     'metaDescription' => $settings('meta_description') ?: '',
    //     'metaKeywords' => $settings('meta_keywords') ?: '',
    // ];

    // View::render('frontend/home', $data, 'layouts/frontend');
?>




