<?php
require 'config.php';
if (empty($_SESSION['cedula'])) header('Location: index.php');

// Procesos: agregar, editar, eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $nombre = trim($_POST['nombre']);
        $unidades = intval($_POST['unidades']);
        $tipo = $_POST['tipo'];
        $bodega = $_POST['bodega'];
        $stmt = $conn->prepare("INSERT INTO articles (nombre,unidades,tipo,bodega) VALUES (?,?,?,?)");
        $stmt->bind_param('siss', $nombre, $unidades, $tipo, $bodega);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $id = intval($_POST['id']);
        $nombre = trim($_POST['nombre']);
        $unidades = intval($_POST['unidades']);
        $tipo = $_POST['tipo'];
        $bodega = $_POST['bodega'];
        $stmt = $conn->prepare("UPDATE articles SET nombre=?, unidades=?, tipo=?, bodega=? WHERE id=?");
        $stmt->bind_param('sissi', $nombre, $unidades, $tipo, $bodega, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $conn->prepare("DELETE FROM articles WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: articles.php');
    exit;
}

// Obtener artículo para edición si aplica
$editArticle = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $editArticle = $res->fetch_assoc();
    $stmt->close();
}

// Listado
$result = $conn->query("SELECT * FROM articles ORDER BY id DESC");
$tipos = ['PC','teclado','disco duro','mouse'];
$bodegas = ['norte','sur','oriente','occidente'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Artículos</title></head>
<body>
<h2>Gestión de Artículos</h2>
<p>Usuario: <?php echo htmlspecialchars($_SESSION['nombre']); ?> — <a href="logout.php">Cerrar sesión</a> — <?php if($_SESSION['cedula']==='1111'){echo '<a href="dashboard.php">Dashboard</a>';} ?></p>

<?php if ($editArticle): ?>
<h3>Editar artículo</h3>
<form method="post">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?php echo $editArticle['id']; ?>">
    Nombre: <input name="nombre" value="<?php echo htmlspecialchars($editArticle['nombre']); ?>" required><br>
    Unidades: <input name="unidades" type="number" value="<?php echo $editArticle['unidades']; ?>" required><br>
    Tipo: <select name="tipo"><?php foreach($tipos as $t){ $sel = $t==$editArticle['tipo']?'selected':''; echo "<option $sel>$t</option>"; } ?></select><br>
    Bodega: <select name="bodega"><?php foreach($bodegas as $b){ $sel = $b==$editArticle['bodega']?'selected':''; echo "<option $sel>$b</option>"; } ?></select><br>
    <button type="submit">Guardar</button>
    <a href="articles.php">Cancelar</a>
</form>
<?php else: ?>
<h3>Agregar artículo</h3>
<form method="post">
    <input type="hidden" name="action" value="add">
    Nombre: <input name="nombre" required><br>
    Unidades: <input name="unidades" type="number" value="0" required><br>
    Tipo: <select name="tipo"><?php foreach($tipos as $t){ echo "<option>$t</option>"; } ?></select><br>
    Bodega: <select name="bodega"><?php foreach($bodegas as $b){ echo "<option>$b</option>"; } ?></select><br>
    <button type="submit">Agregar</button>
</form>
<?php endif; ?>

<h3>Listado de artículos</h3>
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>Nombre</th><th>Unidades</th><th>Tipo</th><th>Bodega</th><th>Acciones</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['nombre']); ?></td>
<td><?php echo $row['unidades']; ?></td>
<td><?php echo htmlspecialchars($row['tipo']); ?></td>
<td><?php echo htmlspecialchars($row['bodega']); ?></td>
<td>
    <a href="articles.php?edit=<?php echo $row['id']; ?>">Editar</a>
    <form method="post" style="display:inline;" onsubmit="return confirm('Eliminar?');">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <button type="submit">Eliminar</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
