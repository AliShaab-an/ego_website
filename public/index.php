<?php
    require_once __DIR__ . '/../app/bootstrap.php';
    

    $frontend = new FrontendController();


    $page = $_GET['page'] ?? 'home';

    $routes = [
        'home' => [
            'roles' => null, // public
            'run' => fn() => $frontend->home(),
        ],
        'shop' => [
            'roles' => null,
            'run' => fn() => $frontend->shop(),
        ],
        'product' => [
            'roles' => null,
            'run' => fn() => $frontend->product(),
        ],
        'category' => [
            'roles' => null,
            'run' => fn() => $frontend->category(),
        ],
        'contact' => [
            'roles' => null,
            'run' => fn() => $frontend->contact(),
        ],

        // Customer-only pages
        'cart' => [
            'roles' => ['customer'],
            'run' => fn() => $frontend->cart(),
        ],
        'checkout' => [
            'roles' => ['customer'],
            'run' => fn() => $frontend->checkout(),
        ],
    ];

    if(!isset($routes[$page])) {
        View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
        exit;
    }

    if (!empty($routes[$page]['roles'])) {
        Authorization::requireRoles($routes[$page]['roles']);
    }

    if (Auth::check() && in_array(Auth::role(), ['admin','super_admin','editor'], true)) {
        // allow them to access public pages, but block cart/checkout
        if (in_array($page, ['cart','checkout'], true)) {
            View::render('errors/403', ['pageKey' => '403'], 'layouts/frontend');
            exit;
        }
    }

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




