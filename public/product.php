<?php 
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once CORE . 'Helper.php';
    require_once CORE . 'View.php';

    Session::configure(1800, url('index.php'), true);
    Session::startSession();

    $data = [
        'pageKey' => 'product',
        'nav_logo' => "assets/images/egologo3.png",
    ];
    View::render('frontend/product', $data, 'layouts/frontend');
?>


    <!-- <div class="h-28 shadow-[0px_-7px_22.5px_0px_rgba(0,0,0,0.25)] py-4">
        <?php 
        //include FRONTEND_VIEWS . '/partials/nav.php'; 
        ?> -->
    