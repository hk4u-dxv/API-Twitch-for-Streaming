<?php

/**
 * Callback para el proceso de autenticación de Twitch
 * Maneja la respuesta del flujo OAuth y procesa el código de autorización
 */

// Incluye los archivos necesarios
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAuth.php';

// Inicia la sesión para almacenar el token
session_start();

// Verifica si hay errores en la autenticación
if (isset($_GET['error'])) {
    // Maneja el error redirigiendo al usuario a la página principal con el mensaje de error
    header('Location: index.php?error=' . urlencode($_GET['error_description']));
    exit;
}

// Procesa el código de autorización si está presente
if (isset($_GET['code'])) {
    try {
        // Prepara los parámetros necesarios para intercambiar el código por un token
        $token_params = [
            'client_id' => TWITCH_CLIENT_ID,
            'client_secret' => TWITCH_CLIENT_SECRET,
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => TWITCH_REDIRECT_URI
        ];

        // Configura y ejecuta la solicitud cURL para obtener el token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TWITCH_TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Obtiene y procesa la respuesta
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);

        // Verifica si se obtuvo el token correctamente
        if (isset($data['access_token'])) {
            // Almacena el token en la sesión y marca al usuario como autenticado
            $_SESSION['twitch_token'] = $data['access_token'];
            $_SESSION['twitch_user'] = true;

            // Redirige al dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            // Lanza una excepción si no se pudo obtener el token
            throw new Exception('Error al obtener el token de acceso');
        }
    } catch (Exception $e) {
        // Maneja cualquier error durante el proceso
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// Si no hay código ni error, redirige al inicio
header('Location: index.php');
exit;
