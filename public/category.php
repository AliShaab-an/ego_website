<?php 
    Session::configure(1800,'/Ego_website/public/index.php');
    Session::startSession();
    $userId = Session::getCurrentUser();
    $sessionId = session_id();

?>