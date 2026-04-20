<?php
    require_once __DIR__ . '/../app/bootstrap.php';

    // ============================================================================
    // MAINTENANCE MODE — blocks all frontend pages, admins bypass it
    // ============================================================================
    if ((bool)getSetting('enable_maintenance', 0) && !Auth::isAdmin()) {
        http_response_code(503);
        $maintenanceMessage = getSetting('maintenance_message', '');
        require_once __DIR__ . '/../app/views/errors/maintenance.php';
        exit;
    }

    $frontend = new FrontendController();


    $page = $_GET['page'] ?? 'home';

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

        // Reset Password - public page (token from email link)
        'resetPassword' => [
            'guard' => null,
            'run' => fn() => $frontend->resetPassword(),
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
        
        // Checkout - guests and customers allowed (admins blocked) - supports guest checkout
        'checkout' => [
            'guard' => fn() => Authorization::allowGuestOrCustomer(),
            'run' => fn() => $frontend->checkout(),
        ],

        // Customer Account - customers only
        'account' => [
            'guard' => fn() => Authorization::requireCustomer(),
            'run' => fn() => $frontend->account(),
        ],
        'order-history' => [
            'guard' => fn() => Authorization::requireCustomer(),
            'run' => fn() => $frontend->orderHistory(),
        ],
        'order-details' => [
            'guard' => fn() => Authorization::requireCustomer(),
            'run' => fn() => $frontend->orderDetails(),
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




