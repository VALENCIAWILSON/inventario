<?php
require_once __DIR__ . '/../src/config.php';
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
