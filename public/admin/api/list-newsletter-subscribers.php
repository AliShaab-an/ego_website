<?php
    header('Content-Type: application/json');
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
        $result = $controller->getSubscribers();
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode($result);
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    ?>
