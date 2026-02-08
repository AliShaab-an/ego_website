<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin']);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Response::error('Invalid request method', null, 405);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $logName = $data['log'] ?? null;
    $location = $data['location'] ?? 'root';

    if (!$logName) {
        Response::error('Log file not specified', null, 400);
    }

    // Sanitize filename
    $logName = basename($logName);

    // Determine log directory
    $logDir = ($location === 'app') 
        ? __DIR__ . '/../../../app/logs/' 
        : __DIR__ . '/../../../logs/';

    $logPath = $logDir . $logName;

    if (!file_exists($logPath)) {
        Response::error('Log file not found', null, 404);
    }

    // Clear the log file
    if (file_put_contents($logPath, '') !== false) {
        Response::json(['success' => true, 'message' => 'Log cleared successfully']);
    } else {
        Response::error('Failed to clear log', null, 500);
    }
});
