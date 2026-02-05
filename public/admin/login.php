<?php
    require_once __DIR__ . '/../../app/config/path.php';
    require_once CORE . 'Session.php';

    Session::configure(1800, url('admin/login.php'), true);
    Session::startSession();

    // If already logged in as admin, redirect to dashboard
    if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'super_admin'])) {
        header('Location: index.php?action=dashboard');
        exit;
    }

    include ADMIN_VIEWS . 'login.php';
?>