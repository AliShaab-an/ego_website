<?php
// Redirect to new front controller routing
$id = $_GET['id'] ?? '';
if ($id) {
    header("Location: index.php?page=product&id=" . urlencode($id));
} else {
    header("Location: index.php?page=shop");
}
exit;
?>


    <!-- <div class="h-28 shadow-[0px_-7px_22.5px_0px_rgba(0,0,0,0.25)] py-4">
        <?php 
        //include FRONTEND_VIEWS . '/partials/nav.php'; 
        ?> -->
    