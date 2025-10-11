<?php
    require_once __DIR__ . '/../app/config/path.php';
    require_once __DIR__ . '/../app/core/Session.php';
    Session::configure(1800,'/Ego_website/public/index.php');
    Session::startSession();
    $userId = Session::getCurrentUser();
    $sessionId = session_id();
    $header_bg = "assets/images/contactus.png";
    $header_title = "Contact us";
    $nav_logo = "assets/images/egologo2.png";
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <title>EGO Luxury-Contact us</title>
</head>
<body class="text-center">
    <?php
    
        include __DIR__ . '/../app/views/frontend/header.php';
        include __DIR__ . '/../app/views/frontend/login.php'; 
        include __DIR__ . '/../app/views/frontend/signup.php';
        include __DIR__ . '/../app/views/frontend/partials/sidebar.php';
        include __DIR__ . '/../app/views/frontend/partials/contactSection.php';
        include __DIR__ . '/../app/views/frontend/footer.php';
    ?>

    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script src="<?= JS_PATH ?>main.js"></script>
    <script src="<?= JS_PATH ?>app.js"></script>
</body> 
</html>