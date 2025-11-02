<?php 
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once MODELS . 'Category.php';
    
    Session::configure(1800, url('index.php'), true);
    Session::startSession();
    $userId = Session::getCurrentUser();
    
    // Get category ID and details
    $categoryId = $_GET['id'] ?? null;
    if (!$categoryId) {
        header("Location: shop.php");
        exit;
    }
    
    try {
        $category = Category::getById($categoryId);
        if (!$category) {
            header("Location: shop.php");
            exit;
        }
    } catch (Exception $e) {
        header("Location: shop.php");
        exit;
    }
    
    $header_bg = "assets/images/shop.png";
    
    $header_title = $category['name'];
    $header_subtitle = $category['description'] ?? "Discover our " . $category['name'] . " collection";
    $nav_logo = "assets/images/egologo2.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/egologo.png">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?= htmlspecialchars($category['name']) ?> - Ego Clothing</title>
</head>
<body class="text-center" data-page="category" data-category-id="<?= $categoryId ?>">
    <?php 
        include FRONTEND_VIEWS . 'header.php';
        include FRONTEND_VIEWS . 'login.php'; 
        include FRONTEND_VIEWS . 'signup.php';
        include FRONTEND_VIEWS. '/partials/sidebar.php';

        include FRONTEND_VIEWS . 'categoryProducts.php';

        include FRONTEND_VIEWS . 'footer.php';
    ?>

    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>main.js"></script>
</body>
</html>