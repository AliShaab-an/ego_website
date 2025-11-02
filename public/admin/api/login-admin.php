<?php

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once CONT . 'UserController.php';

    Session::configure(1800, url('admin/login.php'), true);
    Session::startSession();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        exit;
    }

    try {
        $controller = new UserController();
        $result = $controller->adminLogin();
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    }
