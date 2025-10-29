<?php
require_once __DIR__ . '/../src/config.php';
require_login();
$mysqli = db_connect();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$err = '';
$nombre = '';
$unidades = 0;
$tipo = 'PC';
$bodega = 'norte';

if ($editing) {
	$stmt = $mysqli->prepare("SELECT nombre, unidades, tipo, bodega FROM articulos WHERE id = ?");
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$r = $stmt->get_result();
	if ($row = $r->fetch_assoc()) {
		$nombre = $row['nombre'];
		$unidades = $row['unidades'];
		$tipo = $row['tipo'];
		$bodega = $row['bodega'];
	} else {
		$err = 'Artículo no encontrado.';
	}
}

$tipos = ['PC','teclado','disco duro','mouse'];
$bodegas = ['norte','sur','oriente','occidente'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$nombre = trim($_POST['nombre'] ?? '');
	$unidades = (int)($_POST['unidades'] ?? 0);
	$tipo = $_POST['tipo'] ?? 'PC';
	$bodega = $_POST['bodega'] ?? 'norte';
	if ($nombre === '') {
		$err = 'Nombre obligatorio.';
	} else {
		if ($editing) {
			$stmt = $mysqli->prepare("UPDATE articulos SET nombre = ?, unidades = ?, tipo = ?, bodega = ? WHERE id = ?");
			$stmt->bind_param('sissi', $nombre, $unidades, $tipo, $bodega, $id);
		} else {
			$stmt = $mysqli->prepare("INSERT INTO articulos (nombre, unidades, tipo, bodega) VALUES (?, ?, ?, ?)");
			$stmt->bind_param('siss', $nombre, $unidades, $tipo, $bodega);
		}
		$stmt->execute();
		header('Location: articles.php');
		exit;
	}
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?php echo $editing ? 'Editar' : 'Agregar'; ?> artículo</title></head>
<body>
	<p><a href="articles.php">Volver</a></p>
	<h1><?php echo $editing ? 'Editar' : 'Agregar'; ?> artículo</h1>
	<?php if ($err): ?><p style="color:red;"><?php echo htmlspecialchars($err); ?></p><?php endif; ?>
	<form method="post">
		<label>Nombre:<br><input name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required></label><br>
		<label>Unidades:<br><input name="unidades" type="number" min="0" value="<?php echo (int)$unidades; ?>" required></label><br>
		<label>Tipo:<br>
			<select name="tipo">
				<?php foreach($tipos as $t): ?>
					<option value="<?php echo $t; ?>" <?php if ($t==$tipo) echo 'selected'; ?>><?php echo $t; ?></option>
				<?php endforeach; ?>
			</select>
		</label><br>
		<label>Bodega:<br>
			<select name="bodega">
				<?php foreach($bodegas as $b): ?>
					<option value="<?php echo $b; ?>" <?php if ($b==$bodega) echo 'selected'; ?>><?php echo $b; ?></option>
				<?php endforeach; ?>
			</select>
		</label><br>
		<button type="submit"><?php echo $editing ? 'Guardar' : 'Crear'; ?></button>
	</form>
</body>
</html>
