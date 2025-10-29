<?php
require_once __DIR__ . '/../src/config.php';
require_login();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard - Inventario</title></head>
<body>
	<p>Usuario: <?php echo htmlspecialchars($_SESSION['user']['nombre']); ?> (<a href="logout.php">Cerrar sesión</a>)</p>
	<h1>Panel</h1>
	<?php if (is_admin()): ?>
		<p><a href="users.php"><button>Gestión de usuarios</button></a></p>
		<p><a href="articles.php"><button>Gestión de artículos</button></a></p>
	<?php else: ?>
		<p><a href="articles.php"><button>Gestión de artículos</button></a></p>
	<?php endif; ?>
</body>
</html>
