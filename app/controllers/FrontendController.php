<?php 

    class FrontendController {

        private $productController;
        private $categories;
        public function __construct()
        {
            $this->productController = new ProductController();
            $this->categories = new CategoryController();
        }

    /**
     * Render a page with consistent defaults from settings
     */
    private function render(string $page, array $data = []): void
    {
        // Set pageKey for active state
        $data['pageKey'] ??= $page;

        // Default SEO meta tags from settings
        $data['metaTitle'] ??= getSetting('meta_title', getSetting('website_name', 'Ego Clothing'));
        $data['metaDescription'] ??= getSetting('meta_description', '');
        $data['metaKeywords'] ??= getSetting('meta_keywords', '');

        // Default header theme (dark = white text, light = dark text)
        $data['header_theme'] ??= 'dark';

        // Choose logo based on theme
        if ($data['header_theme'] === 'dark') {
            // Dark background, use light logo
            $data['nav_logo'] ??= getSetting('logo_light') 
                ? asset('admin/uploads/settings/' . getSetting('logo_light'))
                : (getSetting('logo') 
                    ? asset('admin/uploads/settings/' . getSetting('logo'))
                    : asset('assets/images/egologo2.png'));
        } else {
            // Light background, use dark logo
            $data['nav_logo'] ??= getSetting('logo_dark')
                ? asset('admin/uploads/settings/' . getSetting('logo_dark'))
                : (getSetting('logo')
                    ? asset('admin/uploads/settings/' . getSetting('logo'))
                    : asset('assets/images/egologo3.png'));
        }

        // Set default header background based on page
        if (!isset($data['header_bg'])) {
            $data['header_bg'] = match($page) {
                'home' => getSetting('homepage_bg') 
                    ? asset('admin/uploads/settings/' . getSetting('homepage_bg'))
                    : asset('assets/images/header2.png'),
                'shop' => getSetting('shop_bg')
                    ? asset('admin/uploads/settings/' . getSetting('shop_bg'))
                    : asset('assets/images/shop.png'),
                'contact' => getSetting('contact_bg')
                    ? asset('admin/uploads/settings/' . getSetting('contact_bg'))
                    : asset('assets/images/contactus.png'),
                default => asset('assets/images/header2.png'),
            };
        }

        // Default header title/subtitle
        $data['header_title'] ??= '';
        $data['header_subtitle'] ??= '';

        View::render('frontend/pages/' . $page, $data, 'layouts/frontend');
    }

    public function home(): void
    {
        $data = [
            'header_theme' => 'dark',
            'header_title' => getSetting('website_name', 'EGO Luxury'),
            'header_subtitle' => 'Modern Chic &amp; Timeless Elegance',

            // Data for sections
            'topProducts' => $this->productController->getTopProducts(),
            'newProducts' => $this->productController->getNewProducts(),
            'shopTheLookProducts' => $this->productController->getProductsByCategoryName('Shop the Look', 8),
            'categoriesWithProducts' => $this->categories->listCategoriesWithProducts(),
        ];

        $this->render('home', $data);
    }

    public function shop(): void
    {
        $data = [
            'header_theme' => 'dark',
            'header_title' => 'Shop',
            'header_subtitle' => 'Discover Our Collection',
            'metaTitle' => 'Shop Women\'s Fashion | ' . getSetting('website_name', 'Ego Clothing'),
            'metaDescription' => 'Browse our collection of trendy women\'s clothing.',
        ];

        $this->render('shop', $data);
    }


    public function contact(): void
    {
        $data = [
            'header_theme' => 'dark',
            'header_title' => 'Contact Us',
            'header_subtitle' => 'We\'d Love to Hear from You',
            'metaTitle' => 'Contact Us | ' . getSetting('website_name', 'Ego Clothing'),
            'metaDescription' => 'Get in touch with ' . getSetting('website_name', 'Ego Clothing') . ' for orders, support, and inquiries.',
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

        $product = $this->productController->getProductById($id);

        if (!$product) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        $data = [
            'header_theme' => 'dark',
            'header_title' => $product['name'] ?? 'Product',
            'header_subtitle' => '',
            'product' => $product,
            'metaTitle' => ($product['name'] ?? 'Product') . ' | ' . getSetting('website_name', 'Ego Clothing'),
            'metaDescription' => $product['description'] ?? '',
        ];

        $this->render('product', $data);
    }

    public function category(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        // Get category details
        $category = Category::getById($id);
        if (!$category) {
            View::render('errors/404', ['pageKey' => '404'], 'layouts/frontend');
            return;
        }

        $data = [
            'header_theme' => 'dark',
            'header_title' => $category['name'] ?? 'Category',
            'header_subtitle' => $category['description'] ?? '',
            'categoryId' => $id,
            'category' => $category,
            'metaTitle' => ($category['name'] ?? 'Category') . ' | ' . getSetting('website_name', 'Ego Clothing'),
            'metaDescription' => $category['description'] ?? '',
        ];

        $this->render('category', $data);
    }

    public function cart(): void
    {
        $data = [
            'header_theme' => 'dark',
            'header_title' => 'Shopping Cart',
            'header_subtitle' => 'Review Your Items',
            'metaTitle' => 'Cart | ' . getSetting('website_name', 'Ego Clothing'),
        ];

        $this->render('cart', $data);
    }

    public function checkout(): void
    {
        $data = [
            'header_theme' => 'light',
            'header_title' => 'Checkout',
            'header_subtitle' => 'Complete Your Order',
            'metaTitle' => 'Checkout | ' . getSetting('website_name', 'Ego Clothing'),
        ];

        $this->render('checkout', $data);
    }
}
    