<?php
    require_once __DIR__ . '/../app/config/path.php';
    $header_bg = "assets/images/header2.png";
    $header_title = "EGO Luxury";
    $header_subtitle = "Modern Chick &amp; Timeless Elegance";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <title>Ego Clothing</title>
</head>
<body class="text-center">
    <?php 
        require_once __DIR__ . '/../app/controllers/CategoryController.php';
        $categoriesController = new CategoryController();
        $categories = $categoriesController->getCategories();

        include __DIR__ . '/../app/views/frontend/header.php';
        include __DIR__ . '/../app/views/frontend/login.php'; 
        include __DIR__ . '/../app/views/frontend/signup.php';
        include __DIR__ . '/../app/views/frontend/partials/sidebar.php';
        
        include __DIR__ . '/../app/views/frontend/partials/collections.php';

        include __DIR__ . '/../app/views/frontend/partials/homeContact.php';
    
    ?>

    

    
    
    <script src="<?= JS_PATH ?>main.js"></script>
</body>
</html>


