<?php


    header('Content-Type: text/csv');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CORE . 'Session.php';
    require_once CORE . 'Auth.php';
    require_once CONT . 'NewsletterController.php';
    require_once CORE . 'Helper.php';

    try {
        Session::configure(900, url('admin/login.php'), false);
        Session::startSession();
        Auth::checkAdmin();
        
        $controller = new NewsletterController();
        $result = $controller->exportCSV();
        
        if ($result['success']) {
            // Set download headers
            header('Content-Disposition: attachment; filename="' . $result['filename'] . '"');
            header('Content-Length: ' . strlen($result['csv']));
            
            echo $result['csv'];
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode($result);
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    ?>
