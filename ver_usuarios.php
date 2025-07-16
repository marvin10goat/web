<?php
require 'db.php';
session_start();

// Verificar que el usuario está logueado y es administrador
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    echo " Acceso denegado. Esta página es solo para administradores.";
    exit();
}

// Consultar el total de usuarios
$stmt = $pdo->query("SELECT COUNT(*) AS total FROM usuarios");
$totalUsuarios = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="login-container">
    <h2>👤 Panel de Administración</h2>
    <p> Total de usuarios registrados: <strong><?= $totalUsuarios ?></strong></p>

    <a href="index.php">🔙 Volver al inicio</a>
</div>
</body>
</html>


