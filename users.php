<?php
require 'config.php';
if (empty($_SESSION['cedula'])) header('Location: index.php');
if (!($_SESSION['cedula'] === '1111')) {
    echo "Acceso denegado.";
    exit;
}

// Procesos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $password = $_POST['password'];
        $stmt = $conn->prepare("INSERT INTO users (cedula,nombre,password) VALUES (?,?,?)");
        $stmt->bind_param('sss', $cedula, $nombre, $password);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre']);
        $password = $_POST['password'];
        $stmt = $conn->prepare("UPDATE users SET nombre=?, password=? WHERE cedula=?");
        $stmt->bind_param('sss', $nombre, $password, $cedula);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: users.php');
    exit;
}

// Editar usuario
$editUser = null;
if (isset($_GET['edit'])) {
    $ced = $_GET['edit'];
    $stmt = $conn->prepare("SELECT cedula,nombre,password FROM users WHERE cedula=?");
    $stmt->bind_param('s', $ced);
    $stmt->execute();
    $res = $stmt->get_result();
    $editUser = $res->fetch_assoc();
    $stmt->close();
}

// Listado
$result = $conn->query("SELECT cedula,nombre FROM users ORDER BY cedula");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Usuarios</title></head>
<body>
<h2>Gestión de Usuarios</h2>
<p><a href="dashboard.php">Volver</a> — <a href="logout.php">Cerrar sesión</a></p>

<?php if ($editUser): ?>
<h3>Editar usuario</h3>
<form method="post">
    <input type="hidden" name="action" value="edit">
    Cédula: <input name="cedula" value="<?php echo htmlspecialchars($editUser['cedula']); ?>" readonly><br>
    Nombre: <input name="nombre" value="<?php echo htmlspecialchars($editUser['nombre']); ?>" required><br>
    Contraseña: <input name="password" value="<?php echo htmlspecialchars($editUser['password']); ?>" required><br>
    <button type="submit">Guardar</button>
    <a href="users.php">Cancelar</a>
</form>
<?php else: ?>
<h3>Agregar usuario</h3>
<form method="post">
    <input type="hidden" name="action" value="add">
    Cédula: <input name="cedula" required><br>
    Nombre: <input name="nombre" required><br>
    Contraseña: <input name="password" required><br>
    <button type="submit">Agregar</button>
</form>
<?php endif; ?>

<h3>Listado de usuarios</h3>
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>Cédula</th><th>Nombre</th><th>Acciones</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($row['cedula']); ?></td>
<td><?php echo htmlspecialchars($row['nombre']); ?></td>
<td><a href="users.php?edit=<?php echo urlencode($row['cedula']); ?>">Editar</a></td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
