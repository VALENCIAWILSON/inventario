<?php
require_once __DIR__ . '/../src/config.php';
if (!empty($_SESSION['user'])) {
	header('Location: dashboard.php');
	exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$cedula = trim($_POST['cedula'] ?? '');
	$password = $_POST['password'] ?? '';
	if ($cedula === '' || $password === '') {
		$err = 'Ingrese cedula y contraseña.';
	} else {
		$mysqli = db_connect();
		$stmt = $mysqli->prepare("SELECT cedula, nombre, password FROM usuarios WHERE cedula = ?");
		$stmt->bind_param('s', $cedula);
		$stmt->execute();
		$res = $stmt->get_result();
		if ($row = $res->fetch_assoc()) {
			if (password_verify($password, $row['password'])) {
				$_SESSION['user'] = ['cedula' => $row['cedula'], 'nombre' => $row['nombre']];
				header('Location: dashboard.php');
				exit;
			} else {
				$err = 'Credenciales inválidas.';
			}
		} else {
			$err = 'Usuario no encontrado.';
		}
	}
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login - Inventario</title></head>
<body>
	<h1>Login</h1>
	<?php if ($err): ?><p style="color:red;"><?php echo htmlspecialchars($err); ?></p><?php endif; ?>
	<form method="post">
		<label>Cédula:<br><input name="cedula" required></label><br>
		<label>Contraseña:<br><input name="password" type="password" required></label><br>
		<button type="submit">Entrar</button>
	</form>
</body>
</html>
