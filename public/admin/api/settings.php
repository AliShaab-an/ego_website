<?php
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT . 'SettingsController.php';
    
    $controller = new SettingsController();
    $action = $_GET['action'] ?? $_POST['action'] ?? 'getSettings';

    $response = [];

    switch ($action) {
        case 'getSettings':
            $response = $controller->getSettings();
            break;

        case 'saveSettings':
            $response = $controller->saveSettings();
            break;

        case 'getSetting':
            $response = $controller->getSetting();
            break;

        case 'saveSetting':
            $response = $controller->saveSetting();
            break;

        case 'validateSmtp':
            $response = $controller->validateSmtp();
            break;

        default:
            $response = ['status' => 'error', 'message' => 'Unknown action'];
    }
} catch (Throwable $e) {
    error_log("Settings API Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    $response = [
        'status' => 'error',
        'message' => 'An error occurred',
        'debug' => (defined('IS_LOCAL') && IS_LOCAL ? $e->getMessage() : null)
    ];
}

echo json_encode($response);
