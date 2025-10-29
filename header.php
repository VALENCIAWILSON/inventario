<?php if(session_status()==PHP_SESSION_NONE)session_start();?>
<!doctype html><html lang='es'><head><meta charset='utf-8'><title>Inventario</title>
<link rel='stylesheet' href='styles.css'></head><body><header><h1>Gestión de Inventario</h1>
<?php if(isset($_SESSION['cedula'])): ?><div class='top'><span>Usuario: <?php echo htmlentities($_SESSION['nombre']); ?></span>
<a class='btn' href='logout.php'>Salir</a></div><?php endif; ?></header><main>