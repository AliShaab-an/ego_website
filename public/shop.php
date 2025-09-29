<?php 
    require_once __DIR__ . '/../app/config/path.php';
    $header_bg = "assets/images/shop.png";
    $header_title = "Shop";
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

        require_once __DIR__ . '/../app/controllers/ProductController.php';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $productController = new ProductController();
        $products = $productController->listAllProducts($page,12);
        $productsCount = $productController->getProductsCount();
        
        include __DIR__ . '/../app/views/frontend/header.php';
        include __DIR__ . '/../app/views/frontend/login.php'; 
        include __DIR__ . '/../app/views/frontend/signup.php';
        include __DIR__ . '/../app/views/frontend/partials/sidebar.php';
        include __DIR__ . '/../app/views/frontend/partials/categoriesSidebar.php';
        include __DIR__ . '/../app/views/frontend/allProducts.php';
        include __DIR__ . '/../app/views/frontend/footer.php';
    ?>

    <script src="<?= JS_PATH ?>main.js"></script>
</body>
</html>