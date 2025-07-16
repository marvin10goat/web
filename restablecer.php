<?php
require 'db.php';

$mensaje = "";

// Obtener token desde la URL
if (!isset($_GET['token'])) {
    $mensaje = "Token de recuperación no válido.";
} else {
    $token = $_GET['token'];

    // Verificar token
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE token_recuperacion = ? AND expiracion_token > NOW()");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        $mensaje = "Token inválido o expirado.";
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nueva = $_POST['nueva_contraseña'];
        $confirmar = $_POST['confirmar_contraseña'];

        if ($nueva !== $confirmar) {
            $mensaje = "Las contraseñas no coinciden.";
        } else {
            // Encriptar nueva contraseña
            $hash = password_hash($nueva, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET contraseña = ?, token_recuperacion = NULL, expiracion_token = NULL WHERE id = ?");
            $stmt->execute([$hash, $usuario['id']]);
            $mensaje = "Contraseña actualizada correctamente. <a href='login.php'>Inicia sesión</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="login-container">
    <h2>Restablecer contraseña</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="mensaje-recuperacion"><?= $mensaje ?></div>
    <?php endif; ?>

    <?php if (isset($usuario) && $usuario && (!isset($_POST['nueva_contraseña']) || 
    $mensaje !== " Contraseña actualizada correctamente. <a href='login.php'>Inicia sesión</a>")): ?>
    <form method="post">
        <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required><br>
        <input type="password" name="confirmar_contraseña" placeholder="Confirmar contraseña" required><br>
        <button type="submit">Guardar nueva contraseña</button>
    </form>
    <?php endif; ?>

    <br>
    <a href="login.php">Volver al login</a>
</div>
</body>
</html>
