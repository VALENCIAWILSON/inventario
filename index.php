<?php
require 'config.php';

if (isset($_POST['cedula'], $_POST['password'])) {
    $cedula = trim($_POST['cedula']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT cedula,nombre,password FROM users WHERE cedula = ?");
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if ($user && $user['password'] === $password) {
        $_SESSION['cedula'] = $user['cedula'];
        $_SESSION['nombre'] = $user['nombre'];
        // Admin sólo si cedula 1111 y password 1234
        if ($user['cedula'] === '1111' && $password === '1234') {
            header('Location: dashboard.php');
            exit;
        } else {
            header('Location: articles.php');
            exit;
        }
    } else {
        $error = 'Credenciales inválidas';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Cédula: <input name="cedula" required><br>
    Contraseña: <input name="password" type="password" required><br>
    <button type="submit">Entrar</button>
</form>
</body>
</html>
