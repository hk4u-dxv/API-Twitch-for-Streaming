<?php
// Agregar al inicio del archivo, después de los requires
header('Content-Type: application/json');

// Verifica que las variables de entorno estén configuradas
if (!file_exists(__DIR__ . '/.env')) {
    die(json_encode([
        'error' => true,
        'mensaje' => 'Error de configuración: Archivo .env no encontrado'
    ]));
}

// Incluye los archivos necesarios
require_once 'config/config.php';
require_once 'includes/ErrorHandler.php';
require_once 'includes/TwitchAPI.php';
require_once 'includes/TwitchStreams.php';

// Registra los manejadores de errores
ErrorHandler::register();

// Verifica que la solicitud sea válida
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Método de solicitud inválido'
    ]);
    exit;
}

try {
    // Inicializa la clase TwitchStreams
    $twitch = new TwitchStreams();

    // Determina qué tipo de datos obtener basado en el parámetro 'tipo'
    $tipo = $_GET['tipo'] ?? 'streams';

    switch ($tipo) {
        case 'streams':
            $resultado = $twitch->obtenerStreamsEnVivo();
            break;

        case 'user':
            if (!isset($_GET['username'])) {
                throw new Exception('Se requiere un nombre de usuario');
            }
            $resultado = $twitch->obtenerInfoUsuario($_GET['username']);
            break;

        case 'game':
            if (!isset($_GET['game_id'])) {
                throw new Exception('Se requiere un ID de juego');
            }
            $resultado = $twitch->obtenerInfoJuego($_GET['game_id']);
            break;

        default:
            throw new Exception('Tipo de solicitud no válido');
    }

    // Devuelve los resultados
    echo json_encode([
        'error' => false,
        'datos' => $resultado
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage()
    ]);
}
