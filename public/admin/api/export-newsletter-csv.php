<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

ApiRunner::run(function () {
    Authorization::requireRoles(['admin', 'super_admin', 'editor']);
    $controller = new NewsletterController();
    $result = $controller->exportCSV();
    
    if ($result['success']) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
        header('Content-Length: ' . strlen($result['csv']));
        echo $result['csv'];
        exit;
    } else {
        Response::error($result['message'] ?? 'Failed to export CSV', null, 500);
    }
});
