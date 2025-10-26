<?php 
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE .'Session.php';
    Session::configure(1800,'/Ego_website/public/index.php', true);
    Session::startSession();
    $userId = Session::getCurrentUser();
    $sessionId = session_id();
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
<body data-page="cart">
    <div class="h-28 shadow-[0px_-7px_22.5px_0px_rgba(0,0,0,0.25)] py-4">
        <?php include __DIR__ . '/../app/views/frontend/partials/nav.php'; ?>
    </div>
    <?php 
        include __DIR__ . '/../app/views/frontend/login.php'; 
        include __DIR__ . '/../app/views/frontend/signup.php';
        include __DIR__ . '/../app/views/frontend/partials/sidebar.php';
        include __DIR__ . '/../app/views/frontend/cartSection.php';
        include __DIR__ . '/../app/views/frontend/footer.php';
    ?>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script type="module" src="assets/js/main.js"></script>
</body>
</html>