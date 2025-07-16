<?php
session_start();
require 'db.php';

$mensaje = "";

// Cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = trim($_POST['correo']);
    $pass = trim($_POST['contraseña']);

    // Buscar usuario por correo
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch();

    // Verificar contraseña
    if ($user && password_verify($pass, $user['contraseña'])) {
        $_SESSION['usuario'] = $user['nombre'];
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['rol_id'] = $user['rol_id'];

        header('Location: index.php');
        exit();
    } else {
        $mensaje = "Credenciales incorrectas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="login-container">
    <h2>Iniciar sesión</h2>

    <?php if ($mensaje): ?>
        <p style="color: red;"><?= $mensaje ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="correo" placeholder="Correo" required><br>
        <input type="password" name="contraseña" placeholder="Contraseña" required><br>
        <button type="submit">Iniciar sesión</button>
    </form>

    <div style="margin-top: 15px;">
        <a href="recuperar.php">¿Olvidaste tu contraseña?</a><br>
        <a href="registro.php">Regístrate aquí</a><br>
        <a href="google_login.php">Iniciar sesión con Google</a>
    </div>
</div>
</body>
</html>
