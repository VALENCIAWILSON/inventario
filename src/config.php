<?php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // ajustar según entorno
$DB_NAME = 'inventario';

function db_connect() {
	global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
	$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	if ($mysqli->connect_errno) {
		die("DB connect error: " . $mysqli->connect_error);
	}
	$mysqli->set_charset('utf8mb4');
	return $mysqli;
}

function require_login() {
	if (empty($_SESSION['user'])) {
		header('Location: index.php');
		exit;
	}
}

function is_admin() {
	return (isset($_SESSION['user']['cedula']) && $_SESSION['user']['cedula'] === 'admin');
}
?>
