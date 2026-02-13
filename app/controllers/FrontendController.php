<?php

class FrontendController
{

    private $productController;
    private $categories;
    public function __construct()
    {
        $this->productController = new ProductController();
        $this->categories = new CategoryController();
    }

    private function render(string $page, array $data = []): void
    {
        // useful for header/sidebar active state
        $data['pageKey'] ??= $page;

        View::render('frontend/pages/' . $page, $data, 'layouts/frontend');
    }

    public function home(): void
    {
        // Get cache version for home page data
        $homeVersion = Cache::get('home:version') ?: 1;

        $data = [
            // Hero configuration
            'hasHero' => true,
            'heroImage' => getSetting('homepage_bg', asset('images/header2.png')),
            'heroTitle' => "EGO Luxury",
            'heroSubtitle' => "Modern Chick &amp; Timeless Elegance",
            'headerVariant' => 'transparent',
            'headerTheme' => 'dark', // white text/icons

            'nav_logo' => asset('images/egologo3.png'),

            // Data needed by your home partials - CACHED for 5 minutes
            'topProducts' => Cache::remember("home:v{$homeVersion}:top_products", 300, 
                fn() => $this->productController->getTopProducts()
            ),
            'newProducts' => Cache::remember("home:v{$homeVersion}:new_products", 300, 
                fn() => $this->productController->getNewProducts()
            ),
            'shopTheLookProducts' => Cache::remember("home:v{$homeVersion}:shop_look", 300, 
                fn() => $this->productController->getProductsByCategoryName('Shop the Look', 8)
            ),
            'categoriesWithProducts' => Cache::remember("home:v{$homeVersion}:categories", 3600, 
                fn() => $this->categories->listCategoriesWithProducts()
            ),

            // SEO (fallback to global settings in layout if null)
            'metaTitle' =>'Ego Clothing',
        ];

        $this->render('home', $data);
    }

    public function shop(): void
    {
        $data = [
            // Hero configuration
            'hasHero' => true,
            'heroImage' => getSetting('shop_bg', asset('images/header2.png')),
            'heroTitle' => "Shop",
            'heroSubtitle' => "",
            'headerVariant' => 'transparent',
            'headerTheme' => 'dark',

            'nav_logo' => asset('images/egologo2.png'),

            // Optional SEO overrides
            'metaTitle' => 'Shop | Ego Clothing',
        ];

        $this->render('shop', $data);
    }


    public function contact(): void
    {
        $data = [
            // Hero configuration
            'hasHero' => true,
            'heroImage' => getSetting('contact_bg', asset("images/contactus.png")),
            'heroTitle' => "Contact us",
            'heroSubtitle' => "",
            'headerVariant' => 'transparent',
            'headerTheme' => 'dark',

            'nav_logo' => asset('images/egologo2.png'),

            'metaTitle' => 'Contact Us | Ego Clothing',
        ];

        $this->render('contact', $data);
    }

    public function product(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        // Get product details
        $product = $this->productController->getProductById($id);

        if (!$product) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        $this->render('product', [
            'product' => $product,
            // No hero for product page
            'hasHero' => false,
            'headerVariant' => 'solid',
            'headerTheme' => 'light', // dark text/icons
            'nav_logo' => asset('images/egologo2.png'),
            'metaTitle' => ($product['name'] ?? 'Product') . ' | ' . getSetting('meta_title', 'Ego Clothing'),
        ]);
    }

    public function category(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        // Get category details
        require_once MODELS . 'Category.php';
        $category = Category::getById($id);

        if (!$category) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        $this->render('category', [
            'category' => $category,
            'categoryId' => $id,
            // Hero configuration
            'hasHero' => true,
            'heroImage' => getSetting('shop_bg', asset('images/header2.png')),
            'heroTitle' => $category['name'] ?? 'Category',
            'heroSubtitle' => '',
            'headerVariant' => 'transparent',
            'headerTheme' => 'dark',
            'nav_logo' => asset('assets/images/egologo2.png'),
            'metaTitle' => ($category['name'] ?? 'Category') . ' | ' . getSetting('meta_title', 'Ego Clothing'),
        ]);
    }

    public function cart(): void
    {
        // Fetch cart data from CartController
        $cartController = new CartController();
        try {
            $cartData = $cartController->getCartItems();
        } catch (Exception $e) {
            error_log("Cart error: " . $e->getMessage());
            $cartData = ['items' => [], 'total' => 0, 'count' => 0];
        }

        $this->render('cart', [
            // No hero for cart page
            'hasHero' => false,
            'headerVariant' => 'solid',
            'headerTheme' => 'light',
            'nav_logo' => asset('images/egologo2.png'),
            'metaTitle' => 'Cart | Ego Clothing',

            // Cart data
            'cartItems' => $cartData['items'] ?? [],
            'cartTotal' => $cartData['total'] ?? 0,
            'cartCount' => $cartData['count'] ?? 0,
        ]);
    }

    public function checkout(): void
    {
        // Fetch cart data
        $cartController = new CartController();
        try {
            $cartData = $cartController->getCartItems();
        } catch (Exception $e) {
            error_log("Cart error: " . $e->getMessage());
            $cartData = ['items' => [], 'total' => 0, 'count' => 0];
        }

        // Get user info if logged in (customer)
        $userName = '';
        $userEmail = '';
        $userPhone = '';

        if (Auth::isCustomer()) {
            $user = Auth::user();
            if ($user) {
                $userName = $user['name'] ?? '';
                $userEmail = $user['email'] ?? '';
                // Fetch full user data for phone if needed
                $fullUser = User::findById($user['id']);
                $userPhone = $fullUser['phone'] ?? '';
            }
        }

        $this->render('checkout', [
            // No hero for checkout page
            'hasHero' => false,
            'headerVariant' => 'solid',
            'headerTheme' => 'light',
            'nav_logo' => asset('images/egologo2.png'),
            'metaTitle' => 'Checkout | Ego Clothing',

            // Cart data
            'cartItems' => $cartData['items'] ?? [],
            'cartTotal' => $cartData['total'] ?? 0,
            'cartCount' => $cartData['count'] ?? 0,

            // User data
            'userName' => $userName,
            'userEmail' => $userEmail,
            'userPhone' => $userPhone,
        ]);
    }

    public function forgotPassword(): void
    {
        $this->render('forgot-password', [
            // No hero for forgot password page
            'hasHero' => false,
            'headerVariant' => 'solid',
            'headerTheme' => 'light',
            'nav_logo' => asset('images/egologo2.png'),
            'metaTitle' => 'Forgot Password | Ego Clothing',
        ]);
    }

    public function privacyPolicy(): void
    {
        $content = getSetting('privacy_policy', '');
        $title = getSetting('privacy_policy_title', 'Privacy Policy');
        
        $this->render('privacy-policy', [
            'pageKey' => 'privacy-policy',
            'hasHero' => false,
            'headerVariant' => 'solid',
            'headerTheme' => 'light',
            'nav_logo' => asset('images/egologo2.png'),
            'metaTitle' => $title . ' | Ego Clothing',
            'privacyTitle' => $title,
            'privacyContent' => $content,
        ]);
    }

    public function termsOfService(): void
    {
        $content = getSetting('terms_conditions', '');
        $title = getSetting('terms_of_service_title', 'Terms & Conditions');
        
        $this->render('terms-of-service', [
            'pageKey' => 'terms-of-service',
            'hasHero' => false,
            'headerVariant' => 'solid',
            'headerTheme' => 'light',
            'nav_logo' => asset('images/egologo2.png'),
            'metaTitle' => $title . ' | Ego Clothing',
            'termsTitle' => $title,
            'termsContent' => $content,
        ]);
    }
}
