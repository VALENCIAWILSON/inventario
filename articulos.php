<?php
require 'db.php';
require 'helpers.php';
require_login();

$action = $_GET['action'] ?? '';

// Agregar artículo
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $unidades = (int)$_POST['unidades'];
    $tipo = $_POST['tipo'];
    $bodega = $_POST['bodega'];
    $stmt = $pdo->prepare('INSERT INTO articulos (nombre, unidades, tipo, bodega) VALUES (?,?,?,?)');
    $stmt->execute([$nombre,$unidades,$tipo,$bodega]);
    header('Location: articulos.php');
    exit;
}

// Editar artículo
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $nombre = $_POST['nombre'];
    $unidades = (int)$_POST['unidades'];
    $tipo = $_POST['tipo'];
    $bodega = $_POST['bodega'];
    $stmt = $pdo->prepare('UPDATE articulos SET nombre=?, unidades=?, tipo=?, bodega=? WHERE id=?');
    $stmt->execute([$nombre,$unidades,$tipo,$bodega,$id]);
    header('Location: articulos.php');
    exit;
}

// Eliminar artículo
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare('DELETE FROM articulos WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: articulos.php');
    exit;
}

// Datos para edición
$editItem = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM articulos WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $editItem = $stmt->fetch();
}

// Listar artículos
$stmt = $pdo->query('SELECT * FROM articulos ORDER BY nombre');
$articulos = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Artículos - Inventario</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Artículos</h1>

    <h2>Lista</h2>
    <table>
      <tr><th>ID</th><th>Nombre</th><th>Unidades</th><th>Tipo</th><th>Bodega</th><th>Acciones</th></tr>
      <?php foreach($articulos as $a): ?>
        <tr>
          <td><?=htmlspecialchars($a['id'])?></td>
          <td><?=htmlspecialchars($a['nombre'])?></td>
          <td><?=htmlspecialchars($a['unidades'])?></td>
          <td><?=htmlspecialchars($a['tipo'])?></td>
          <td><?=htmlspecialchars($a['bodega'])?></td>
          <td>
            <a href="articulos.php?action=edit&id=<?=urlencode($a['id'])?>">Editar</a>
            <a href="articulos.php?action=delete&id=<?=urlencode($a['id'])?>" onclick="return confirm('Eliminar artículo?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <h2><?= $editItem ? 'Editar artículo' : 'Agregar artículo' ?></h2>
    <form method="post" action="articulos.php?action=<?= $editItem ? 'edit' : 'add' ?>">
      <?php if ($editItem): ?>
        <input type="hidden" name="id" value="<?=htmlspecialchars($editItem['id'])?>">
      <?php endif; ?>
      <label>Nombre<br><input name="nombre" value="<?= $editItem ? htmlspecialchars($editItem['nombre']) : '' ?>" required></label>
      <label>Unidades<br><input name="unidades" type="number" min="0" value="<?= $editItem ? htmlspecialchars($editItem['unidades']) : 0 ?>" required></label>
      <label>Tipo<br>
        <select name="tipo" required>
          <?php $tipos = ['PC','teclado','disco duro','mouse'];
            foreach($tipos as $t) {
              $sel = ($editItem && $editItem['tipo']===$t) ? 'selected' : '';
              echo "<option value=\"$t\" $sel>$t</option>";
            }
          ?>
        </select>
      </label>
      <label>Bodega<br>
        <select name="bodega" required>
          <?php $bodegas = ['norte','sur','oriente','occidente'];
            foreach($bodegas as $b) {
              $sel = ($editItem && $editItem['bodega']===$b) ? 'selected' : '';
              echo "<option value=\"$b\" $sel>$b</option>";
            }
          ?>
        </select>
      </label>
      <button type="submit"><?= $editItem ? 'Guardar cambios' : 'Agregar artículo' ?></button>
    </form>

    <p><a href="index.php">Volver</a></p>
  </div>
</body>
</html>
