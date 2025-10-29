<?php
require_once __DIR__ . '/../src/config.php';
require_login();
if (!is_admin()) {
	http_response_code(403);
	echo "Acceso denegado.";
	exit;
}
$mysqli = db_connect();
$cedula = $_GET['cedula'] ?? '';
$editing = $cedula !== '';
$err = '';
$name = '';
if ($editing) {
	$stmt = $mysqli->prepare("SELECT cedula, nombre FROM usuarios WHERE cedula = ?");
	$stmt->bind_param('s', $cedula);
	$stmt->execute();
	$r = $stmt->get_result();
	if ($row = $r->fetch_assoc()) {
		$name = $row['nombre'];
	} else {
		$err = 'Usuario no encontrado.';
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$cedula_post = trim($_POST['cedula'] ?? '');
	$nombre = trim($_POST['nombre'] ?? '');
	$password = $_POST['password'] ?? '';
	if ($cedula_post === '' || $nombre === '') {
		$err = 'Cédula y nombre son obligatorios.';
	} else {
		if (isset($_POST['editing'])) {
			// editar
			if ($password === '') {
				$stmt = $mysqli->prepare("UPDATE usuarios SET nombre = ? WHERE cedula = ?");
				$stmt->bind_param('ss', $nombre, $cedula_post);
			} else {
				$hash = password_hash($password, PASSWORD_DEFAULT);
				$stmt = $mysqli->prepare("UPDATE usuarios SET nombre = ?, password = ? WHERE cedula = ?");
				$stmt->bind_param('sss', $nombre, $hash, $cedula_post);
			}
			$stmt->execute();
			header('Location: users.php');
			exit;
		} else {
			// agregar
			$hash = password_hash($password ?: '1234', PASSWORD_DEFAULT);
			$stmt = $mysqli->prepare("INSERT INTO usuarios (cedula, nombre, password) VALUES (?, ?, ?)");
			$stmt->bind_param('sss', $cedula_post, $nombre, $hash);
			if (!$stmt->execute()) {
				$err = 'Error al crear usuario (verifique cedula).';
			} else {
				header('Location: users.php');
				exit;
			}
		}
	}
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?php echo $editing ? 'Editar' : 'Agregar'; ?> usuario</title></head>
<body>
	<p><a href="users.php">Volver</a></p>
	<h1><?php echo $editing ? 'Editar' : 'Agregar'; ?> usuario</h1>
	<?php if ($err): ?><p style="color:red;"><?php echo htmlspecialchars($err); ?></p><?php endif; ?>
	<form method="post">
		<label>Cédula:<br>
			<input name="cedula" value="<?php echo htmlspecialchars($editing ? $cedula : ''); ?>" <?php echo $editing ? 'readonly' : 'required'; ?>>
		</label><br>
		<label>Nombre:<br>
			<input name="nombre" value="<?php echo htmlspecialchars($editing ? $name : ''); ?>" required>
		</label><br>
		<label>Contraseña:<br>
			<input name="password" type="password"> <?php if (!$editing) echo '(si se deja vacío la contraseña por defecto será 1234)'; ?>
		</label><br>
		<?php if ($editing): ?><input type="hidden" name="editing" value="1"><?php endif; ?>
		<button type="submit"><?php echo $editing ? 'Guardar' : 'Crear'; ?></button>
	</form>
</body>
</html>
