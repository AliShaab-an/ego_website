<?php
header("Location: index.php?page=resetPassword&token=" . urlencode($_GET['token'] ?? ''));
exit;
