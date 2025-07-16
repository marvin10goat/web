<?php
session_start();
require 'db.php';

// sSolo puede entrar el administrador (rol_id = 1)
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    echo "Acceso denegado. Esta sección es solo para administradores.";
    exit();
}

// Buscar usuarios por nombre o correo
$busqueda = '';
if (isset($_GET['buscar'])) {
    $busqueda = trim($_GET['buscar']);
    $stmt = $pdo->prepare("
        SELECT u.id, u.nombre, u.correo, r.nombre AS rol
        FROM usuarios u
        LEFT JOIN roles r ON u.rol_id = r.id
        WHERE u.nombre LIKE ? OR u.correo LIKE ?
    ");
    $stmt->execute(["%$busqueda%", "%$busqueda%"]);
} else {
    $stmt = $pdo->query("
        SELECT u.id, u.nombre, u.correo, r.nombre AS rol
        FROM usuarios u
        LEFT JOIN roles r ON u.rol_id = r.id
    ");
}
$usuarios = $stmt->fetchAll();

// Obtener todos los roles para el <select>
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

// Cambiar rol si se envió formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_rol'])) {
    $usuario_id = $_POST['usuario_id'];
    $nuevo_rol = $_POST['nuevo_rol'];
    $stmt = $pdo->prepare("UPDATE usuarios SET rol_id = ? WHERE id = ?");
    $stmt->execute([$nuevo_rol, $usuario_id]);
    header("Location: admin_usuarios.php");
    exit();
}

// Eliminar usuario si se envió la acción
if (isset($_GET['eliminar'])) {
    $eliminar_id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$eliminar_id]);
    header("Location: admin_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar usuarios</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="login-container" style="width: 90%; max-width: 1000px;">
    <h2>Gestión de Usuarios (Solo Administrador)</h2>

    <!--  Buscador -->
    <form method="get" style="margin-bottom: 20px;">
        <input type="text" name="buscar" placeholder="Buscar por nombre o correo" value="<?= htmlspecialchars($busqueda) ?>">
        <button type="submit">Buscar</button>
        <a href="admin_usuarios.php">Limpiar</a>
    </form>

    <!-- Tabla de usuarios -->
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <tr style="background-color: #f0f0f0;">
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol actual</th>
            <th>Cambiar rol</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario['id'] ?></td>
            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
            <td><?= htmlspecialchars($usuario['correo']) ?></td>
            <td><?= $usuario['rol'] ?? 'Sin rol' ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="usuario_id" value="<?= $usuario['id'] ?>">
                    <select name="nuevo_rol">
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>" <?= ($rol['nombre'] == $usuario['rol']) ? 'selected' : '' ?>>
                                <?= $rol['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="cambiar_rol">✔</button>
                </form>
            </td>
            <td>
                <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                    <a href="admin_usuarios.php?eliminar=<?= $usuario['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                <?php else: ?>
                    (Tú)
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="index.php"> Volver al inicio</a>
</div>
</body>
</html>  