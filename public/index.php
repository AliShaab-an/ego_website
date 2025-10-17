<?php
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    Session::configure(1800,'/Ego_website/public/index.php');
    Session::startSession();
    $userId = Session::getCurrentUser();
    $sessionId = session_id();
    $header_bg = "assets/images/header2.png";
    $header_title = "EGO Luxury";
    $header_subtitle = "Modern Chick &amp; Timeless Elegance";
    $nav_logo = "assets/images/egologo2.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Ego Clothing</title>
</head>
<body class="text-center">
    <?php 
        // require_once __DIR__ . '/../app/controllers/CategoryController.php';
        // require_once __DIR__ . '/../app/controllers/ProductController.php';
        // $categoriesController = new CategoryController();
        // $productController = new ProductController();
        // $result = $categoriesController->listCategoriesWithProducts();
        // $categoryAndProducts = $result['data'];

        // $topProducts = $productController->getTopProducts();

        // $newProducts = $productController->getNewProducts();

        include __DIR__ . '/../app/views/frontend/header.php';
        include __DIR__ . '/../app/views/frontend/login.php'; 
        include __DIR__ . '/../app/views/frontend/signup.php';
        include __DIR__ . '/../app/views/frontend/partials/sidebar.php';
        
        
        // include __DIR__ . '/../app/views/frontend/partials/topProducts.php';
        // include __DIR__ . '/../app/views/frontend/partials/newProducts.php';

        include __DIR__ . '/../app/views/frontend/partials/homeContact.php';

        include __DIR__ . '/../app/views/frontend/footer.php';
    
    ?>

    


    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="<?= JS_PATH ?>main.js"></script>
    <script src="<?= JS_PATH ?>app.js"></script>
</body>
</html>


