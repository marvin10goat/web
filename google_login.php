<?php
require_once 'vendor/autoload.php'; // Carga la librería de Google
session_start();

//  Reemplazamos con tu Client ID y Client Secret de Google Console
$clientID = '251172666525-564pb37jmaesu76b52q18j4ebn5n36en.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/gestion_usuarios/google_login.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    
    
    

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $google_user = $google_oauth->userinfo->get();

        // Guardar datos del usuario en sesión
        $_SESSION['usuario'] = $google_user->name;
        $_SESSION['correo'] = $google_user->email;

        // Redirigir al inicio
        header('Location: index.php');
        exit();
    } else {
        echo "Error al iniciar sesión con Google.";
    }
}

// Crear URL de inicio de sesión con Google
$login_url = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login con Google</title>
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar sesión con Google</h2>
        <a class="google-button" href="<?= $login_url ?>">Entrar con Google</a>
    </div>
</body>
</html>
