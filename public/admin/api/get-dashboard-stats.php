<?php

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT . 'AdminController.php';
    
    try{
        $adminController = new AdminController();
        $adminController->getDashboardStats();
    }catch (Throwable $e) {
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
        
