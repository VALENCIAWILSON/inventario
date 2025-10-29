<?php
require 'helpers.php';
require_login();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Inventario</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Bienvenido <?=htmlspecialchars($_SESSION['nombre'])?></h1>
    <?php if (is_admin()): ?>
      <p>
        <a class="btn" href="users.php">Gestionar Usuarios</a>
        <a class="btn" href="articulos.php">Gestionar Artículos</a>
      </p>
    <?php else: ?>
      <p>
        <a class="btn" href="articulos.php">Gestionar Artículos</a>
      </p>
    <?php endif; ?>
    <p><a href="logout.php">Cerrar sesión</a></p>
  </div>
</body>
</html>
