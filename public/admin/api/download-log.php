<?php
require_once __DIR__ . '/../../../app/config/path.php';
require_once CORE . 'Session.php';

Session::configure(1800, url('admin/login.php'), true);
Session::startSession();

// Check if user is admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$logName = $_GET['log'] ?? null;
$location = $_GET['location'] ?? 'root';

if (!$logName) {
    http_response_code(400);
    exit('Log file not specified');
}

// Sanitize filename
$logName = basename($logName);

// Determine log directory
$logDir = ($location === 'app') 
    ? __DIR__ . '/../../../app/logs/' 
    : __DIR__ . '/../../../logs/';

$logPath = $logDir . $logName;

if (!file_exists($logPath)) {
    http_response_code(404);
    exit('Log file not found');
}

// Send file for download
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="' . $logName . '"');
header('Content-Length: ' . filesize($logPath));
readfile($logPath);
exit;
