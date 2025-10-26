<?php 
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    Session::configure(1800,'/Ego_website/public/index.php', true);
    Session::startSession();
    $userId = Session::getCurrentUser();
    $nav_logo = "assets/images/egologo3.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Ego Clothing</title>
</head>
<body data-page="product">
    <div class="h-28 shadow-[0px_-7px_22.5px_0px_rgba(0,0,0,0.25)] py-4">
        <?php 
        include FRONTEND_VIEWS . '/partials/nav.php'; 
        ?>
    </div>
    <?php
        include FRONTEND_VIEWS . 'login.php'; 
        include FRONTEND_VIEWS . 'signup.php';
        include FRONTEND_VIEWS . '/partials/sidebar.php';
        
        include FRONTEND_VIEWS . 'productCard.php';
        
        
        include FRONTEND_VIEWS . 'footer.php';
    ?>

    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>main.js"></script>
</body>
</html>