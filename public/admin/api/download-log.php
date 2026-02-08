<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin']);
    
    $logName = $_GET['log'] ?? null;
    $location = $_GET['location'] ?? 'root';

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

    // Send file for download
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $logName . '"');
    header('Content-Length: ' . filesize($logPath));
    readfile($logPath);
    exit;
});
