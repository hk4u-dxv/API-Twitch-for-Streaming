<?php

/**
 * Endpoint que maneja diferentes tipos de solicitudes de datos de Twitch
 */

// Establece el tipo de contenido como JSON para la respuesta
header('Content-Type: application/json');

// Verifica que las variables de entorno estén configuradas
if (!file_exists(__DIR__ . '/.env')) {
    die(json_encode([
        'error' => true,
        'mensaje' => 'Error de configuración: Archivo .env no encontrado'
    ]));
}

// Incluye los archivos necesarios para el funcionamiento
require_once 'config/config.php';
require_once 'includes/ErrorHandler.php';
require_once 'includes/TwitchAPI.php';
require_once 'includes/TwitchStreams.php';

// Registra los manejadores de errores personalizados
ErrorHandler::register();

// Verifica que la solicitud sea mediante método GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Método de solicitud inválido'
    ]);
    exit;
}

try {
    // Inicializa la clase TwitchStreams para acceder a la API
    $twitch = new TwitchStreams();

    // Determina qué tipo de datos obtener basado en el parámetro 'tipo'
    $tipo = $_GET['tipo'] ?? 'streams';

    // Procesa diferentes tipos de solicitudes
    switch ($tipo) {
        case 'streams':
            // Obtiene lista de streams en vivo
            $resultado = $twitch->obtenerStreamsEnVivo();
            break;

        case 'user':
            // Verifica y obtiene información de un usuario específico
            if (!isset($_GET['username'])) {
                throw new Exception('Se requiere un nombre de usuario');
            }
            // Obtiene información del usuario
            $resultado = $twitch->obtenerInfoUsuario($_GET['username']);
            break;

        case 'game':
            // Verifica y obtiene información de un juego específico
            if (!isset($_GET['game_id'])) {
                throw new Exception('Se requiere un ID de juego');
            }
            // Obtiene información del juego
            $resultado = $twitch->obtenerInfoJuego($_GET['game_id']);
            break;

        default:
            // Maneja tipos de solicitud no válidos
            throw new Exception('Tipo de solicitud no válido');
    }

    // Devuelve los resultados en formato JSON
    echo json_encode([
        'error' => false,
        'datos' => $resultado
    ]);
} catch (Exception $e) {
    // Maneja y devuelve cualquier error que ocurra durante el proceso
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage()
    ]);
}
