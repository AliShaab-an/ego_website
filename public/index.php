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

        // Forgot Password - public page
        'forgotPassword' => [
            'guard' => null,
            'run' => fn() => $frontend->forgotPassword(),
        ],

        // Privacy Policy - public page
        'privacy-policy' => [
            'guard' => null,
            'run' => fn() => $frontend->privacyPolicy(),
        ],

        // Terms of Service - public page
        'terms-of-service' => [
            'guard' => null,
            'run' => fn() => $frontend->termsOfService(),
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
?>




