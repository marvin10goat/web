<?php
$host = 'localhost';
$db = 'gestion_usuarios';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    exit();
}
?>
