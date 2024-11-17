<?php

/**
 * Endpoint AJAX que obtiene y almacena la clave del stream en la sesión
 */

// Incluye los archivos de configuración y clases necesarias
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/TwitchAPI.php';

// Inicia la sesión para almacenar la clave del stream
session_start();

// Establece el tipo de contenido como JSON para la respuesta
header('Content-Type: application/json');

try {
    // Crea una instancia de la API de Twitch con las credenciales
    $twitchAPI = new TwitchAPI(TWITCH_CLIENT_ID, $_SESSION['twitch_token']);

    // Obtiene la clave del stream del usuario autenticado
    $streamKey = $twitchAPI->getStreamKey();

    // Almacena la clave del stream en la sesión para uso posterior
    $_SESSION['stream_key'] = $streamKey;

    // Devuelve una respuesta exitosa
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // En caso de error, devuelve un mensaje de error detallado
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
