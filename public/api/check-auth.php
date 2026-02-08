<?php
require_once __DIR__ . '/../../app/bootstrap.php';

ApiRunner::run(function () {
    $isLoggedIn = Auth::check();
    
    Response::json([
        'success' => true,
        'isLoggedIn' => $isLoggedIn,
        'user' => $isLoggedIn ? Auth::user() : null
    ]);
});
