<?php
require 'db.php';
require 'helpers.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT cedula, nombre, password FROM usuarios WHERE cedula = ? LIMIT 1');
    $stmt->execute([$cedula]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        // login ok
        $_SESSION['cedula'] = $user['cedula'];
        $_SESSION['nombre'] = $user['nombre'];
        header('Location: index.php');
        exit;
    } else {
        $err = 'Cédula o contraseña incorrecta.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - Inventario</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Iniciar sesión</h1>
    <?php if ($err): ?><p class="error"><?=htmlspecialchars($err)?></p><?php endif; ?>
    <form method="post" action="">
      <label>Cédula<br><input name="cedula" required></label>
      <label>Contraseña<br><input name="password" type="password" required></label>
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
