<?php
// Redirect to new front controller routing
$id = $_GET['id'] ?? '';
if ($id) {
    header("Location: index.php?page=category&id=" . urlencode($id));
} else {
    header("Location: index.php?page=shop");
}
exit;
?>