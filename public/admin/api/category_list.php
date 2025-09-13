<?php
require_once __DIR__ . '/../../../app/controllers/CategoryController.php';

header('Content-Type: application/json');

$controller = new CategoryController();
echo json_encode($controller->listCategories());
