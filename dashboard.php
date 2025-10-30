<?php
require 'config.php';
if (empty($_SESSION['cedula'])) header('Location: index.php');
if (!($_SESSION['cedula'] === '1111')) {
    header('Location: articles.php');
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
<h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h2>
<p>
    <a href="users.php"><button>Gestión de Usuarios</button></a>
    <a href="articles.php"><button>Gestión de Artículos</button></a>
    <a href="logout.php"><button>Cerrar sesión</button></a>
</p>
</body>
</html>
