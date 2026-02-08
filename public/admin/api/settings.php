<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin', 'editor']);
    
    $controller = new SettingsController();
    $action = $_GET['action'] ?? $_POST['action'] ?? 'getSettings';

    switch ($action) {
        case 'getSettings':
            $result = $controller->getSettings();
            Response::json($result);
            break;

        case 'saveSettings':
            $result = $controller->saveSettings();
            Response::json($result);
            break;

        case 'getSetting':
            $result = $controller->getSetting();
            Response::json($result);
            break;

        case 'saveSetting':
            $result = $controller->saveSetting();
            Response::json($result);
            break;

        case 'validateSmtp':
            $result = $controller->validateSmtp();
            Response::json($result);
            break;

        default:
            Response::error('Unknown action', null, 400);
    }
});
