<?php

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CORE . 'Session.php';

    Session::configure(1800, url('admin/login.php'), true);
    Session::startSession();

    header('Content-Type: application/json');

    // Check if user is admin
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $logName = $data['log'] ?? null;
    $location = $data['location'] ?? 'root';

    if (!$logName) {
        echo json_encode(['success' => false, 'message' => 'Log file not specified']);
        exit;
    }

    // Sanitize filename
    $logName = basename($logName);

    // Determine log directory
    $logDir = ($location === 'app') 
        ? __DIR__ . '/../../../app/logs/' 
        : __DIR__ . '/../../../logs/';

    $logPath = $logDir . $logName;

    if (!file_exists($logPath)) {
        echo json_encode(['success' => false, 'message' => 'Log file not found']);
        exit;
    }

    // Clear the log file
    if (file_put_contents($logPath, '') !== false) {
        echo json_encode(['success' => true, 'message' => 'Log cleared successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to clear log']);
    }
