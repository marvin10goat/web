<?php
session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Variables
$nombre = $_SESSION['usuario'];
$rol_id = $_SESSION['rol_id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Gestión de usuarios</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="index-container">
    <h1>Bienvenido, <?= htmlspecialchars($nombre) ?> </h1>
    <p>Tu rol ID: <?= $rol_id ?></p>

    <div class="acciones">
        <a href="logout.php">Cerrar sesión</a><br>
        <a href="roles/ver_permisos.php">Ver Permisos</a><br>

        <?php if ($rol_id == 1): ?>
            <a href="admin_usuarios.php">⚙ Panel de administración</a><br>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
