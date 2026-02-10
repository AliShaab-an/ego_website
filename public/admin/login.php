<?php
    require_once __DIR__ . '/../../app/bootstrap.php';

    Session::configure(1800, url('admin/login.php'), true);
    Session::startSession();

    // If already logged in as admin, redirect to dashboard
    if (Auth::isAdmin()) {
        header('Location: index.php?action=dashboard');
        exit;
    }

    include ADMIN_VIEWS . 'login.php';
?>