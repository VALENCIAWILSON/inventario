<?php
require_once __DIR__ . '/../src/config.php';
require_login();
$mysqli = db_connect();

// eliminar si se solicita
if (isset($_GET['delete'])) {
	$id = (int)$_GET['delete'];
	$stmt = $mysqli->prepare("DELETE FROM articulos WHERE id = ?");
	$stmt->bind_param('i', $id);
	$stmt->execute();
	header('Location: articles.php');
	exit;
}

$res = $mysqli->query("SELECT id, nombre, unidades, tipo, bodega FROM articulos ORDER BY nombre");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Artículos - Inventario</title></head>
<body>
	<p><a href="dashboard.php">Volver</a> | <a href="logout.php">Cerrar sesión</a></p>
	<h1>Artículos</h1>
	<p><a href="article_form.php">Agregar artículo</a></p>
	<table border="1" cellpadding="4">
		<tr><th>Nombre</th><th>Unidades</th><th>Tipo</th><th>Bodega</th><th>Acciones</th></tr>
		<?php while($a = $res->fetch_assoc()): ?>
			<tr>
				<td><?php echo htmlspecialchars($a['nombre']); ?></td>
				<td><?php echo (int)$a['unidades']; ?></td>
				<td><?php echo htmlspecialchars($a['tipo']); ?></td>
				<td><?php echo htmlspecialchars($a['bodega']); ?></td>
				<td>
					<a href="article_form.php?id=<?php echo $a['id']; ?>">Editar</a>
					|
					<a href="articles.php?delete=<?php echo $a['id']; ?>" onclick="return confirm('Eliminar?');">Eliminar</a>
				</td>
			</tr>
		<?php endwhile; ?>
	</table>
</body>
</html>
