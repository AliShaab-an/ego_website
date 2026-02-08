<?php 
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE .'Session.php';
    require_once CORE . 'Helper.php';
    require_once CORE . 'View.php';

    Session::configure(1800, url('index.php'), true);
    Session::startSession();
    
    $data = [
        "pageKey" => "cart",
        'nav_logo' => "assets/images/egologo3.png",
    ];
    View::render('frontend/cart', $data, 'layouts/frontend');
?>

