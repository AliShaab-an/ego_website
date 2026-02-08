<?php
    require_once __DIR__ . '/../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once CORE . 'Helper.php';
    require_once CORE . 'View.php';

    Session::configure(1800, url('index.php'), true);
    Session::startSession();
    
    $data = [
        'pageKey' => 'contact',
        'header_bg' => "assets/images/contactus.png",
        'header_title' => "Contact us",
        'header_subtitle' => "",
        'nav_logo' => "assets/images/egologo2.png",
    ];

    View::render('frontend/contact', $data, 'layouts/frontend');
?>
