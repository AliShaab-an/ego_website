<?php
    session_start();
    header('Content-Type: application/json');

    try {
        $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
        
        echo json_encode([
            'success' => true,
            'isLoggedIn' => $isLoggedIn,
            'user' => $isLoggedIn ? [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['username'] ?? '',
                'email' => $_SESSION['email'] ?? ''
            ] : null
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
