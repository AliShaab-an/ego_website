<?php
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once CONT . 'frontend/ProductController.php';
    
    Session::configure(1800,'/Ego_website/public/index.php', true);
    Session::startSession();
    $userId = Session::getCurrentUser();
    $header_bg = "assets/images/header2.png";
    $header_title = "EGO Luxury";
    $header_subtitle = "Modern Chick &amp; Timeless Elegance";
    $nav_logo = "assets/images/egologo2.png";

    // Fetch products data
    $productController = new ProductController();
    $topProducts = $productController->getTopProducts();
    $newProducts = $productController->getNewProducts();
    
    // Fetch categories with products for collections section
    require_once MODELS . 'Category.php';
    $categoriesWithProducts = Category::getCategoriesWithProducts(4);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Ego Clothing</title>
</head>
<body class="text-center" data-page="home">
    <?php 

        include FRONTEND_VIEWS . 'header.php';
        include FRONTEND_VIEWS . 'login.php'; 
        include FRONTEND_VIEWS . 'signup.php';
        include FRONTEND_VIEWS . '/partials/sidebar.php';
        
        // Collections section
        include FRONTEND_VIEWS . '/partials/collections.php';
        include FRONTEND_VIEWS. '/partials/topProducts.php';
        include FRONTEND_VIEWS . '/partials/newProducts.php';
        include FRONTEND_VIEWS . '/partials/homeContact.php';
        include FRONTEND_VIEWS . 'footer.php';
    ?>
    <div id="loaderOverlay"
    class="fixed inset-0 bg-white/80 flex items-center justify-center z-[9999] hidden">
        <div class="loader border-4 border-gray-200 border-t-brand rounded-full w-10 h-10 animate-spin"></div>
    </div>
    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>main.js"></script>
</body>
</html>


