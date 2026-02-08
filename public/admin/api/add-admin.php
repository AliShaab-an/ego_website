<?php 
    require_once __DIR__ . '/../../../app/bootstrap.php';

    ApiRunner::run(function(){
        Authorization::requireRoles(['admin','super_admin']);
        $controller = new UserController();
        $result = $controller->addAdmin();
        Response::json($result,200);
    });
    