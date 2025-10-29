<?php
require 'db.php';
require 'helpers.php';
require_login();

// Solo admin puede acceder a gestion de usuarios
if (!is_admin()) {
    echo "Acceso denegado. Solo admin puede gestionar usuarios.";
    exit;
}

$action = $_GET['action'] ?? '';

// Agregar usuario
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO usuarios (cedula,nombre,password) VALUES (?,?,?)');
    $stmt->execute([$cedula,$nombre,$password]);
    header('Location: users.php');
    exit;
}

// Modificar usuario
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, password = ? WHERE cedula = ?');
        $stmt->execute([$nombre,$password,$cedula]);
    } else {
        $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ? WHERE cedula = ?');
        $stmt->execute([$nombre,$cedula]);
    }
    header('Location: users.php');
    exit;
}

// Datos para formulario edición
$editUser = null;
if ($action === 'edit' && isset($_GET['cedula'])) {
    $stmt = $pdo->prepare('SELECT cedula,nombre FROM usuarios WHERE cedula = ?');
    $stmt->execute([$_GET['cedula']]);
    $editUser = $stmt->fetch();
}

// Listar usuarios
$stmt = $pdo->query('SELECT cedula,nombre FROM usuarios ORDER BY nombre');
$usuarios = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Usuarios - Inventario</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Usuarios</h1>

    <h2>Lista de usuarios</h2>
    <table>
      <tr><th>Cédula</th><th>Nombre</th><th>Acciones</th></tr>
      <?php foreach($usuarios as $u): ?>
        <tr>
          <td><?=htmlspecialchars($u['cedula'])?></td>
          <td><?=htmlspecialchars($u['nombre'])?></td>
          <td><a href="users.php?action=edit&cedula=<?=urlencode($u['cedula'])?>">Editar</a></td>
        </tr>
      <?php endforeach; ?>
    </table>

    <h2><?= $editUser ? 'Editar usuario' : 'Agregar usuario' ?></h2>
    <form method="post" action="users.php?action=<?= $editUser ? 'edit' : 'add' ?>">
      <label>Cédula<br><input name="cedula" value="<?= $editUser ? htmlspecialchars($editUser['cedula']) : '' ?>" <?= $editUser ? 'readonly' : 'required' ?>></label>
      <label>Nombre<br><input name="nombre" value="<?= $editUser ? htmlspecialchars($editUser['nombre']) : '' ?>" required></label>
      <label>Contraseña <?= $editUser ? '(dejar vacío para no cambiar)' : '' ?><br><input name="password" type="password" <?= $editUser ? '' : 'required' ?>></label>
      <button type="submit"><?= $editUser ? 'Guardar cambios' : 'Agregar usuario' ?></button>
    </form>

    <p><a href="index.php">Volver</a></p>
  </div>
</body>
</html>
