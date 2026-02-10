<?php
// Redirect to new front controller routing
$id = $_GET['id'] ?? '';
if ($id) {
    header("Location: index.php?page=category&id=" . urlencode($id));
} else {
    header("Location: index.php?page=shop");
}
exit;
?>
    
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
        include PARTIALS . 'frontend/header.php';
        include PARTIALS . 'frontend/login-model.php'; 
        include PARTIALS . 'frontend/signup-model.php';
        include PARTIALS . 'frontend/sidebar.php';

        View::partial('frontend/sections/category-products');

        include PARTIALS . 'frontend/footer.php';
    ?>

    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>main.js"></script>
</body>
</html>