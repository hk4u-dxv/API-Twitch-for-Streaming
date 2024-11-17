<?php

/**
 * Maneja el proceso de autenticación OAuth con la API de Twitch
 */

// Incluye los archivos necesarios
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAuth.php';

// Inicia la sesión para manejar el estado de autenticación
session_start();

// Parámetros necesarios para la autenticación de Twitch
$params = [
    'client_id' => TWITCH_CLIENT_ID,           // ID de cliente de la aplicación
    'redirect_uri' => TWITCH_REDIRECT_URI,     // URL de callback después de la autenticación
    'response_type' => 'code',                 // Tipo de respuesta esperada
    'scope' => 'user:read:email channel:read:stream_key', // Permisos solicitados
    'force_verify' => 'true'                   // Fuerza la verificación del usuario
];

// Construye la URL de autorización con los parámetros
$auth_url = TWITCH_AUTH_URL . '?' . http_build_query($params);

// Redirige al usuario a la página de autorización de Twitch
header('Location: ' . $auth_url);
exit;
