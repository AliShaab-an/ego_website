<?php
    // Start output buffering to prevent header issues
    ob_start();

    require_once __DIR__ . '/../../app/controllers/AdminController.php';

    $controller = new AdminController();
    $controller->login();

    ?>