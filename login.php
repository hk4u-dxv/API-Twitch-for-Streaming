<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAuth.php';

session_start();

// Parámetros para la autenticación de Twitch
$params = [
    'client_id' => TWITCH_CLIENT_ID,
    'redirect_uri' => TWITCH_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'user:read:email', // Ajusta los scopes según necesites
    'force_verify' => 'true'
];

// Construye la URL de autorización
$auth_url = TWITCH_AUTH_URL . '?' . http_build_query($params);

// Redirige al usuario a la página de autorización de Twitch
header('Location: ' . $auth_url);
exit;
?> 