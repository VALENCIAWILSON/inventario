<?php
session_start();

$db_host = 'mysql-wilsonv.alwaysdata.net';
$db_user = 'wilsonv';
$db_pass = 'Misifu123+'; // actualizar si aplica
$db_name = "wilsonv_inventario;

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die('DB connection error: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
