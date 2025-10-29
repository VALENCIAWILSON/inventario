<?php
require_once __DIR__ . '/../src/config.php';
require_login();
if (!is_admin()) {
	http_response_code(403);
	echo "Acceso denegado.";
	exit;
}
$mysqli = db_connect();
$res = $mysqli->query("SELECT cedula, nombre FROM usuarios ORDER BY nombre");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Usuarios - Inventario</title></head>
<body>
	<p><a href="dashboard.php">Volver</a> | <a href="logout.php">Cerrar sesión</a></p>
	<h1>Usuarios</h1>
	<p><a href="user_form.php">Agregar usuario</a></p>
	<table border="1" cellpadding="4">
		<tr><th>Cédula</th><th>Nombre</th><th>Acciones</th></tr>
		<?php while($u = $res->fetch_assoc()): ?>
			<tr>
				<td><?php echo htmlspecialchars($u['cedula']); ?></td>
				<td><?php echo htmlspecialchars($u['nombre']); ?></td>
				<td><a href="user_form.php?cedula=<?php echo urlencode($u['cedula']); ?>">Editar</a></td>
			</tr>
		<?php endwhile; ?>
	</table>
</body>
</html>
