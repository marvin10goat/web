<?php
require 'db.php';

$mensaje = "";

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);

    // Verificar si el correo existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar token
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar token en base de datos
        $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacion = ?, expiracion_token = ? WHERE id = ?");
        $stmt->execute([$token, $expira, $user['id']]);

        // Mostrar mensaje con link de prueba
        $link = "http://localhost/gestion_usuarios/restablecer.php?token=" . $token;
        $mensaje = "🔗 Hemos enviado un link de recuperación:<br><a href='$link'>$link</a>";
    } else {
        $mensaje = " El correo no está registrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar cuenta</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="login-container">
    <h2>¿Olvidaste tu contraseña?</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje-recuperacion">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="correo" placeholder="Ingresa tu correo" required><br>
        <button type="submit">Enviar enlace</button>
    </form>

    <br>
    <a href="login.php">Volver a iniciar sesión</a>
</div>
</body>
</html>
