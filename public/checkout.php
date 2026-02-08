<?php 
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once CORE . 'Helper.php';
    require_once CORE . 'View.php';

    Session::configure(1800, url('index.php'), true);
    Session::startSession();

    $data = [
        'pageKey' => 'checkout',
        'nav_logo' => "assets/images/egologo3.png",
    ];
    

    View::render('frontend/checkout', $data, 'layouts/frontend');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/images/egologo.png">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <script src="https://kit.fontawesome.com/7f6ab6587f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.min.css" />

    <link rel="stylesheet" href="assets/css/style.css">
    <title>Ego Clothing</title>
</head>
<body data-page="checkout">
    <div class="h-28 shadow-[0px_-7px_22.5px_0px_rgba(0,0,0,0.25)] py-4">
        <?php include PARTIALS . 'frontend/nav.php'; ?>
    </div>
    <?php 
        include PARTIALS . 'frontend/login-model.php'; 
        include PARTIALS . 'frontend/signup-model.php';
        include PARTIALS . 'frontend/sidebar.php';
        View::partial('frontend/sections/checkout-section');
        include PARTIALS . 'frontend/footer.php';
        
    ?>

    <script src="<?= JS_PATH ?>jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script type="module" src="<?= JS_PATH ?>main.js"></script>
</body>
</html>