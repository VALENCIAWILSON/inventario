<?php
session_start();
function is_logged() {
    return isset($_SESSION['cedula']);
}
function is_admin() {
    return (isset($_SESSION['cedula']) && $_SESSION['cedula'] === '1111');
}
function require_login() {
    if (!is_logged()) {
        header('Location: login.php');
        exit;
    }
}
