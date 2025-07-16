<?php
require 'db.php';

// Registrar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contraseña = password_hash(trim($_POST['contraseña']), PASSWORD_DEFAULT);
    $rol_id = $_POST['rol'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contraseña, rol_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $contraseña, $rol_id]);

    echo "<p>Usuario registrado correctamente</p>";
}

// Obtener roles para el select
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar usuario</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
<div class="login-container">
    <h2>Crear nuevo usuario</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre completo" required><br>
        <input type="email" name="correo" placeholder="Correo electrónico" required><br>
        <input type="password" name="contraseña" placeholder="Contraseña" required><br>
        <select name="rol" required>
            <option value="">Seleccionar rol</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['id'] ?>"><?= $rol['nombre'] ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">Registrar</button>
    </form>
    <a href="login.php">Volver al login</a>
</div>
</body>
</html>
