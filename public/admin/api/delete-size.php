<?php

    require_once __DIR__ . '/../../../app/config/path.php';
    require_once CONT . 'SizesController.php';
    header('Content-Type: application/json');

    try {
        $controller = new SizesController();
        echo json_encode($controller->deleteSize());
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }