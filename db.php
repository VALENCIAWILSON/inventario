<?php
// db.php - Conexión PDO
$host = 'mysql-wilsonv.alwaysdata.net';
$db   = 'wilsonv_inventario';
$user = 'wilsonv';
$pass = 'Misifu123+';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Error de conexión a BD: ' . htmlspecialchars($e->getMessage());
    exit;
}
