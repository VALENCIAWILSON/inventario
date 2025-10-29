<?php
$host='mysql-wilsonv.alwaysdata.net';$db='wilsonv_inventario';$user='wilsonv';$pass='Misifu123+';$charset='utf8mb4';
$dsn="mysql:host=$host;dbname=$db;charset=$charset";
$options=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false];
try{$pdo=new PDO($dsn,$user,$pass,$options);}catch(PDOException $e){exit('Error:'.$e->getMessage());}
session_start();
function is_logged_in(){return isset($_SESSION['cedula']);}
function is_admin(){return is_logged_in()&&$_SESSION['cedula']==='1111';}
?>